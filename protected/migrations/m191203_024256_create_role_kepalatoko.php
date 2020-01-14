<?php

class m191203_024256_create_role_kepalatoko extends CDbMigration
{

    public function safeUp()
    {

        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

        $params = [
            [':nama' => 'KEPALA_TOKO', ':tipe' => 2, ':deskripsi' => 'Kepala Toko'],
            [':nama' => 'transaksiSO-simpan', ':tipe' => 1, ':deskripsi' => 'Transaksi SO minus Simpan'],
            [':nama' => 'transaksiPenjualan-simpan', ':tipe' => 1, ':deskripsi' => 'Transaksi Penjualan minus Simpan'],
            [':nama' => 'transaksiReturPembelian-simpan', ':tipe' => 1, ':deskripsi' => 'Transaksi Retur Pembelian minus Simpan'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }


        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.gantiinput'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.gantirak'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.hapus'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.hapusdetail'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.index'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.inputqtymanual'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.scanbarcode'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.setinaktif'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.setinaktifall'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.setnol'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.setnolall'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.tambah'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.tambahdetail'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.ubah'],
            [':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.view'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.ambilprofil'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.exportcsv'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.hapus'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.hapusdetail'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.import'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.index'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.poin'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.printdraftinvoice'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.printinvoice'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.printnota'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.printstruk'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.tambah'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.tambahdetail'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.total'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.ubah'],
            [':parent' => 'transaksiPenjualan-simpan', ':child' => 'penjualan.view'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.ambilprofil'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.caribarang'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.getbaranginfo'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.hapus'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.hapusdetail'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.index'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.pilihinv'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.printreturpembelian'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.tambah'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.total'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.ubah'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.updateqty'],
            [':parent' => 'transaksiReturPembelian-simpan', ':child' => 'returpembelian.view'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configApp'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configBarang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configDevice'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configDiskon'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configItemPenerimaan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configItemPengeluaran'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configJenisTransaksi'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configKasBank'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configKategoriBarang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configKategoriPenerimaan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configKategoriPengeluaran'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configMember'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configProfil'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configRakBarang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configSatuanBarang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configTagBarang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'configUser'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'kasirAdmin'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'laporanSemua'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'toolsSemua'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiDataHarian'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiHutangPiutang'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPembelian'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPenerimaan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPengeluaran'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPenjualan-simpan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPesananPos'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPO'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiPos'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiReturPembelian-simpan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiReturPenjualan'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiSalesOrder'],
            [':parent' => 'KEPALA_TOKO', ':child' => 'transaksiSO-simpan'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m191203_024256_create_role_kepalatoko does not support migration down.\n";
        return false;
    }

}
