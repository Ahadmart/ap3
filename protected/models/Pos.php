<?php

class Pos extends Penjualan
{

    const KATEGORI_TRX      = 3; //Kategori Penerimaan untuk POS
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
            $this->simpanPenjualan();

            $penerimaan                     = new Penerimaan;
            $penerimaan->tanggal            = date('d-m-Y');
            //$penerimaan->referensi = '[POS]';
            //$penerimaan->tanggal_referensi = date('d-m-Y');
            $penerimaan->profil_id          = $this->profil_id;
            $penerimaan->kas_bank_id        = $posData['account'];
            $penerimaan->jenis_transaksi_id = $posData['jenistr'];
            $penerimaan->kategori_id        = self::KATEGORI_TRX;
            $penerimaan->uang_dibayar       = $posData['uang'];
            $penerimaan->save();

            $penjualan       = Penjualan::model()->findByPk($this->id);
            $hutangPiutangId = $penjualan->hutang_piutang_id;
            $dokumen         = HutangPiutang::model()->findByPk($hutangPiutangId);
            if (is_null($dokumen)) {
                die(serialize($penjualan->attributes));
            }
            $item = $dokumen->itemBayarHutang;

            $penerimaanDetail                    = new PenerimaanDetail;
            $penerimaanDetail->penerimaan_id     = $penerimaan->id;
            $penerimaanDetail->item_id           = $item['itemId'];
            $penerimaanDetail->hutang_piutang_id = $hutangPiutangId;
            $penerimaanDetail->keterangan        = '[POS] ' . $dokumen->keterangan();
            $penerimaanDetail->jumlah            = $dokumen->sisa;

            $penerimaanDetail->save();

            $penerimaanLoad = Penerimaan::model()->findByPk($penerimaan->id);
            if (!$penerimaanLoad->prosesP()) {
                throw new Exception("Gagal proses penerimaan", 500);
            }

//            if (!$penerimaan->prosesP()) {
//                throw new Exception("Gagal proses penerimaan", 500);
//            }

            $transaction->commit();
            return array(
                'sukses' => true
            );
        } catch (Exception $ex) {
            $transaction->rollback();
            return array(
                'sukses' => false,
                'error'  => array(
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ));
        }
    }

    public function inputAkm($nomor)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {

            $tahun = date('y');
            $akm   = Akm::model()->find('substring(nomor,9)*1=:nomor and substring(nomor,5,2)=:tahun',
                    [
                ':nomor' => $nomor,
                ':tahun' => $tahun
            ]);
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
            ]];
        }
    }

    public function inputPesanan($id)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {

            $tahun   = date('y');
            $pesanan = PesananPenjualan::model()->findByPk($id);
            if (is_null($pesanan)) {
                throw new Exception('Pesanan tidak ditemukan', 500);
            }

            $pesananDetails = PesananPenjualanDetail::model()->findAll('pesanan_penjualan_id=:pesananId',
                    [':pesananId' => $pesanan->id]);
            foreach ($pesananDetails as $detail) {
                $barang = Barang::model()->findByPk($detail->barang_id);
                $this->tambahBarangProc($barang, $detail->qty);
            }

            PesananPenjualan::model()->updateByPk($id,
                    [
                'status'       => PesananPenjualan::STATUS_JUAL,
                'penjualan_id' => $this->id
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
            ]];
        }
    }

    public function inputPesananByNomor($nomor)
    {
        $tahun   = date('y');
        $pesanan = PesananPenjualan::model()->find('substring(nomor,9)*1=:nomor and substring(nomor,5,2)=:tahun',
                [
            ':nomor' => $nomor,
            ':tahun' => $tahun
        ]);
        if (is_null($pesanan)) {
            throw new Exception('Pesanan tidak ditemukan', 500);
        }
        return $this->inputPesanan($pesanan->id);
    }

}
