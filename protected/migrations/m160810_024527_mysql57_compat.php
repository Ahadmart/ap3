<?php

class m160810_024527_mysql57_compat extends CDbMigration
{

    public function up()
    {

        $this->execute("
        UPDATE `barang` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_diskon` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_harga_jual` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_harga_jual_rekomendasi` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_kategori` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_rak` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `barang_satuan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `config` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `device` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `harga_pokok_penjualan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `hutang_piutang` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `hutang_piutang_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `inventory_balance` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `item_keuangan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `jenis_transaksi` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `jurnal` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `jurnal_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `kas_bank` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `kasir` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `kode_akun` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `kode_dokumen` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `label_rak_cetak` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `laporan_harian` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `member_periode_poin` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `pembelian` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `pembelian_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penerimaan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penerimaan_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penerimaan_kategori` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `pengeluaran` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `pengeluaran_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `pengeluaran_kategori` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penjualan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penjualan_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penjualan_detail_h` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penjualan_diskon` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `penjualan_member` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `profil` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `profil_tipe` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `retur_pembelian` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `retur_pembelian_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `retur_penjualan` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `retur_penjualan_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `stock_opname` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `stock_opname_detail` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `supplier_barang` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `theme` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
        UPDATE `user` SET created_at = '2000-01-01 00:00:00' WHERE IFNULL(created_at, 0) = 0;
                    ");
    }

    public function down()
    {
        echo "m160810_024527_mysql57_compat does not support migration down.\n";
        return false;
    }

}
