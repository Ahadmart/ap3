<?php
class PenjualanHelper
{
    /**
     * Menyimpan barang yang dihapus ke tabel penjualan_detail_h
     * @param PenjualanDetail $detail
     * @param int $jenis
     */
    public static function simpanHapusDetail($detail, $jenis = PenjualanDetailHapus::JENIS_PER_BARANG)
    {
        $userAdmin = User::model()->findByPk(Yii::app()->user->getState('kasirOtorisasiUserId'));

        // Jika kasirOtorisasi tidak ada, berarti dilakukan dari transaksi-penjualan (tidak perlu otorisasi),
        // karena loginnya sudah punya otorisasi, admin diisi oleh user yang login
        if (is_null($userAdmin)) {
            $userAdmin = User::model()->findByPk(Yii::app()->user->id);
        }
        $penjualanHapus                  = new PenjualanDetailHapus;
        $penjualanHapus->barang_id       = $detail->barang_id;
        $penjualanHapus->barang_barcode  = $detail->barang->barcode;
        $penjualanHapus->barang_nama     = $detail->barang->nama;
        $penjualanHapus->harga_beli      = InventoryBalance::model()->getHargaBeliAwal($detail->barang_id);
        $penjualanHapus->harga_jual      = $detail->harga_jual;
        $penjualanHapus->user_kasir_id   = $detail->updated_by;
        $penjualanHapus->user_kasir_nama = $detail->updatedBy->nama;
        $penjualanHapus->user_admin_id   = $userAdmin->id;
        $penjualanHapus->user_admin_nama = $userAdmin->nama;
        $penjualanHapus->penjualan_id    = $detail->penjualan_id;
        $penjualanHapus->jenis           = $jenis;
        $penjualanHapus->save();
    }

    /**
     * Menyimpan semua detail yang ada di penjualan yang akan dihapus
     * @param int $penjualanId
     */
    public static function simpanHapus($penjualanId)
    {
        $details = PenjualanDetail::model()->findAll('penjualan_id = :penjualanId', [':penjualanId' => $penjualanId]);
        foreach ($details as $detail) {
            self::simpanHapusDetail($detail, PenjualanDetailHapus::JENIS_PER_NOTA);
        }
    }
}
