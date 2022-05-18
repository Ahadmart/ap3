<?php

class Pos extends Penjualan
{
    const KATEGORI_TRX = 3; //Kategori Penerimaan untuk POS
    /* Cari barang */
    const CARI_AUTOCOMPLETE = 0;
    const CARI_TABLE        = 1;

    /**
     * Simpan POS
     * 1. Simpan penjualan
     * 2. Proses penerimaan
     */
    public function simpanPOS($posData)
    {
        $transaction    = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanPenjualan';
        try {
            if (isset($posData['tarik-tunai']) && $posData['tarik-tunai'] > 0) {
                $tarikTunaiMinBelanja = Config::model()->find("nama='pos.tariktunaiminlimit'")->nilai;
                if ($posData['tarik-tunai'] < $tarikTunaiMinBelanja) {
                    throw new Exception('Tarik Tunai belum boleh dilakukan!');
                }
            }
            $this->simpanPenjualan();
            if ($this->profil->tipe_id == Profil::TIPE_MEMBER_ONLINE) {
                $penjualanMOL          = PenjualanMemberOnline::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $this->id]);
                $postPenjualanMemberOL = [
                    'userName'    => Yii::app()->user->namaLengkap,
                    'nomorMember' => $penjualanMOL->nomor_member,
                    'nomor'       => $this->nomor,
                    'total'       => $this->ambilTotal(),
                    'koinDipakai' => $posData['koin-mol'],
                ];

                $clientAPI = new AhadMembershipClient();
                $r         = json_decode($clientAPI->penjualan($postPenjualanMemberOL));
                if (isset($r->error)) {
                    throw new Exception('Ahad Membership Error: ' . $r->error->type . ': ' . $r->error->description, 500);
                }
                $dataPenjualanMOL = $r->data->member;

                // $penjualanMOL->koin_dipakai = 0; //update me!
                $penjualanMOL->poin         = $dataPenjualanMOL->poin;
                $penjualanMOL->koin_dipakai = empty($posData['koin-mol']) ? 0 : $posData['koin-mol'];
                $penjualanMOL->koin         = $dataPenjualanMOL->koin;
                $penjualanMOL->level        = $dataPenjualanMOL->level;
                $penjualanMOL->level_nama   = $dataPenjualanMOL->levelNama;
                $penjualanMOL->total_poin   = $dataPenjualanMOL->totalPoin;
                $penjualanMOL->total_koin   = $dataPenjualanMOL->totalKoin;
                if (!$penjualanMOL->save()) {
                    throw new Exception('Gagal simpan penjualan_member_online: ' . print_r($penjualanMOL->getErrors(), true), 500);
                }
            }

            $uangDibayar = 0;
            $bayar       = [];
            foreach ($posData['bayar'] as $key => $value) {
                $val = empty($value) ? 0 : $value;
                $bayar[] = [
                    'akun'   => $key,
                    'jumlah' => $val,
                ];
                // Yii::log("Bayar detail; akun: " . $key . ", value: " . $value, "info");
                $uangDibayar += $val;
            }

            $penerimaan          = new Penerimaan;
            $penerimaan->tanggal = date('d-m-Y');
            //$penerimaan->referensi = '[POS]';
            //$penerimaan->tanggal_referensi = date('d-m-Y');
            $penerimaan->profil_id          = $this->profil_id;
            $penerimaan->kas_bank_id        = $posData['account'];
            $penerimaan->jenis_transaksi_id = $posData['jenistr'];
            $penerimaan->kategori_id        = self::KATEGORI_TRX;

            $penerimaan->uang_dibayar = $uangDibayar;
            if (!$penerimaan->save()) {
                throw new Exception('Gagal simpan penerimaan', 500);
            }

            $penjualan       = Penjualan::model()->findByPk($this->id);
            $hutangPiutangId = $penjualan->hutang_piutang_id;
            $dokumen         = HutangPiutang::model()->findByPk($hutangPiutangId);
            if (is_null($dokumen)) {
                throw new Exception('Gagal! Hutang Piutang tidak ditemukan: ' . serialize($penjualan->attributes));
            }
            $item = $dokumen->itemBayarHutang;

            foreach ($bayar as $b) {
                if ($b['jumlah'] > 0) {
                    $penerimaanKasBank                = new PenerimaanKasBank;
                    $penerimaanKasBank->penerimaan_id = $penerimaan->id;
                    $penerimaanKasBank->kas_bank_id   = $b['akun'];
                    $penerimaanKasBank->jumlah        = $b['jumlah'];
                    if (!$penerimaanKasBank->save()) {
                        throw new Exception('Gagal simpan penerimaan akun:' . $b['akun'] . ', jml:' . $b['jumlah'], 500);
                    }
                }
            }

            $penerimaanDetail                    = new PenerimaanDetail;
            $penerimaanDetail->penerimaan_id     = $penerimaan->id;
            $penerimaanDetail->item_id           = $item['itemId'];
            $penerimaanDetail->hutang_piutang_id = $hutangPiutangId;
            $penerimaanDetail->keterangan        = '[POS] ' . $dokumen->keterangan();
            $penerimaanDetail->jumlah            = $dokumen->sisa;

            $penerimaanDetail->save();

            if (isset($posData['infaq']) && $posData['infaq'] > 0) {
                $penerimaanDetail                = new PenerimaanDetail;
                $penerimaanDetail->penerimaan_id = $penerimaan->id;
                $penerimaanDetail->item_id       = ItemKeuangan::POS_INFAQ;
                $penerimaanDetail->keterangan    = '[POS] Infak/Sedekah (' . $dokumen->keterangan() . ')';
                $penerimaanDetail->jumlah        = $posData['infaq'];

                $penerimaanDetail->save();
            }

            if (isset($posData['diskon-nota']) && $posData['diskon-nota'] > 0) {
                $penerimaanDetail                = new PenerimaanDetail;
                $penerimaanDetail->penerimaan_id = $penerimaan->id;
                $penerimaanDetail->item_id       = ItemKeuangan::POS_DISKON_PER_NOTA;
                $penerimaanDetail->keterangan    = '[POS] Diskon (' . $dokumen->keterangan() . ')';
                $penerimaanDetail->jumlah        = $posData['diskon-nota'];

                $penerimaanDetail->save();
            }

            if (isset($posData['koin-mol']) && $posData['koin-mol'] > 0) {
                $penerimaanDetail                = new PenerimaanDetail;
                $penerimaanDetail->penerimaan_id = $penerimaan->id;
                $penerimaanDetail->item_id       = ItemKeuangan::POS_KOINCASHBACK_DIPAKAI;
                $penerimaanDetail->keterangan    = '[POS] Koin Cashback dipakai (' . $dokumen->keterangan() . ')';
                $penerimaanDetail->jumlah        = $posData['koin-mol'];

                $penerimaanDetail->save();
            }
            //$tarikTunai = $posData['tarik-tunai'];

            if (isset($posData['tarik-tunai']) && $posData['tarik-tunai'] > 0) {
                $tarikTunaiAkun                  = KasBank::model()->findByPk($posData['tarik-tunai-acc']);
                $penerimaanDetail                = new PenerimaanDetail;
                $penerimaanDetail->penerimaan_id = $penerimaan->id;
                $penerimaanDetail->item_id       = ItemKeuangan::POS_TARIK_TUNAI_PENERIMAAN;
                $penerimaanDetail->keterangan    = '[POS] Penerimaan Tarik Tunai (' . $tarikTunaiAkun->nama . ')';
                $penerimaanDetail->jumlah        = $posData['tarik-tunai'];

                $penerimaanDetail->save();

                $kategoriPengeluaranPOS = KategoriPengeluaran::model()->find('nama=:nama', [':nama' => 'POS']);

                $pengeluaran                     = new Pengeluaran;
                $pengeluaran->referensi          = $penjualan->nomor;
                $pengeluaran->tanggal_referensi  = date('d-m-Y');
                $pengeluaran->tanggal            = date('d-m-Y');
                $pengeluaran->profil_id          = $this->profil_id;
                $pengeluaran->kas_bank_id        = KasBank::KAS_ID;
                $pengeluaran->jenis_transaksi_id = $posData['jenistr'];
                $pengeluaran->kategori_id        = is_null($kategoriPengeluaranPOS) ? 1 : $kategoriPengeluaranPOS->id;

                $pengeluaran->uang_dibayar = $posData['tarik-tunai'];
                if (!$pengeluaran->save()) {
                    throw new Exception('Gagal simpan pengeluaran', 500);
                }

                $pengeluaranDetail                 = new PengeluaranDetail;
                $pengeluaranDetail->pengeluaran_id = $pengeluaran->id;
                $pengeluaranDetail->item_id        = ItemKeuangan::POS_TARIK_TUNAI_PENGELUARAN;
                $pengeluaranDetail->keterangan     = '[POS] Tarik Tunai (' . $tarikTunaiAkun->nama . ')';
                $pengeluaranDetail->jumlah         = $posData['tarik-tunai'];

                $pengeluaranDetail->save();

                $pengeluaranLoad = Pengeluaran::model()->findByPk($pengeluaran->id);
                if (!$pengeluaranLoad->prosesP()) {
                    throw new Exception('Gagal proses pengeluaran', 500);
                }

                $tarikTunaiModel               = new PenjualanTarikTunai;
                $tarikTunaiModel->kas_bank_id  = $posData['tarik-tunai-acc'];
                $tarikTunaiModel->penjualan_id = $penjualan->id;
                $tarikTunaiModel->jumlah       = $posData['tarik-tunai'];

                if (!$tarikTunaiModel->save()) {
                    throw new Exception('Gagal simpan pencatatat Tarik Tunai', 500);
                }
            }

            $penerimaanLoad = Penerimaan::model()->findByPk($penerimaan->id);
            if (!$penerimaanLoad->prosesP()) {
                throw new Exception('Gagal proses penerimaan', 500);
            }

            $transaction->commit();

            return [
                'sukses' => true,
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }

    public function inputAkm($nomor)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $tahun = date('y');
            $akm   = Akm::model()->find(
                'substring(nomor,9)*1=:nomor and substring(nomor,5,2)=:tahun',
                [
                    ':nomor' => $nomor,
                    ':tahun' => $tahun,
                ]
            );
            if (is_null($akm)) {
                throw new Exception('AKM tidak ditemukan', 500);
            }

            $akmDetails = AkmDetail::model()->findAll('akm_id=:akmId', [':akmId' => $akm->id]);
            foreach ($akmDetails as $detail) {
                $barang = Barang::model()->findByPk($detail->barang_id);
                $this->tambahBarangProc($barang, $detail->qty);
            }

            $transaction->commit();
            return [
                'sukses' => true,
            ];
        } catch (Exception $ex) {
            //            echo $exc->getTraceAsString();
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }

    public function inputPesanan($id)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $tahun   = date('y');
            $pesanan = So::model()->findByPk($id);
            if (is_null($pesanan)) {
                throw new Exception('Pesanan (Sales Order) tidak ditemukan', 500);
            }

            $pesananDetails = SoDetail::model()->findAll(
                'so_id=:pesananId',
                [':pesananId' => $pesanan->id]
            );
            foreach ($pesananDetails as $detail) {
                $barang = Barang::model()->findByPk($detail->barang_id);
                $this->tambahBarangProc($barang, $detail->qty);
            }

            So::model()->updateByPk(
                $id,
                [
                    'status'       => So::STATUS_JUAL,
                    'penjualan_id' => $this->id,
                ]
            );

            $transaction->commit();
            return [
                'sukses' => true,
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
                ],
            ];
        }
    }

    public function inputPesananByNomor($nomor)
    {
        $tahun   = date('y');
        $pesanan = So::model()->find(
            'substring(nomor,9)*1=:nomor and substring(nomor,5,2)=:tahun',
            [
                ':nomor' => $nomor,
                ':tahun' => $tahun,
            ]
        );
        if (is_null($pesanan)) {
            throw new Exception('Pesanan tidak ditemukan', 500);
        }
        return $this->inputPesanan($pesanan->id);
    }
}
