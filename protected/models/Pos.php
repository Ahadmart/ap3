<?php

class Pos extends Penjualan
{

    const KATEGORI_TRX = 1;

    /**
     * Simpan POS
     * 1. Simpan penjualan
     * 2. Proses penerimaan
     */
    public function simpanPOS($posData)
    {
        $transaction = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanPenjualan';
        try {
            $this->simpanPenjualan();

            $penerimaan = new Penerimaan;
            $penerimaan->tanggal = date('d-m-Y');
            $penerimaan->referensi = '[POS]';
            $penerimaan->tanggal_referensi = date('d-m-Y');
            $penerimaan->profil_id = $this->profil_id;
            $penerimaan->kas_bank_id = $posData['account'];
            $penerimaan->jenis_transaksi_id = $posData['jenistr'];
            $penerimaan->kategori_id = self::KATEGORI_TRX;
            $penerimaan->uang_dibayar = $posData['uang'];
            $penerimaan->save();

            $penjualan = Penjualan::model()->findByPk($this->id);
            $hutangPiutangId = $penjualan->hutang_piutang_id;
            $dokumen = HutangPiutang::model()->findByPk($hutangPiutangId);
            if (is_null($dokumen)) {
                die(serialize($penjualan->attributes));
            }
            $item = $dokumen->itemBayarHutang;

            $penerimaanDetail = new PenerimaanDetail;
            $penerimaanDetail->penerimaan_id = $penerimaan->id;
            $penerimaanDetail->item_id = $item['itemId'];
            $penerimaanDetail->hutang_piutang_id = $hutangPiutangId;
            $penerimaanDetail->keterangan = '[POS] ' . $dokumen->keterangan();
            $penerimaanDetail->jumlah = $dokumen->sisa;

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
                'error' => array(
                    'msg' => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ));
        }
    }

}
