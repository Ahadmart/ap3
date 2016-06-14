<?php

class m160614_015818_create_role_POS extends CDbMigration
{

    public function safeUp()
    {
        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

        $params = [
            [':nama' => 'pos.adminlogin', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.adminlogout', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.caribarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.cekharga', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.ganticustomer', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.hapus', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.index', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.kembalian', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.out', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.simpan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.suspended', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.tambah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.tambahbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.ubah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.updatehargamanual', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.updateqty', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'penjualan.printstruk', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'penjualan.total', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/customerdisplay.getinfo', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/customerdisplay.index', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'tools/cekharga.cekbarcode', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'transaksiPos', ':tipe' => 1, ':deskripsi' => 'Transaksi POS (Point Of Sales)'],
            [':nama' => 'customerdisplay', ':tipe' => 1, ':deskripsi' => ''],
            [':nama' => 'cekharga', ':tipe' => 1, ':deskripsi' => ''],
            [':nama' => 'POS', ':tipe' => 2, ':deskripsi' => 'Penjualan / Point Of Sales'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiPos', ':child' => 'penjualan.printstruk'],
            [':parent' => 'transaksiPos', ':child' => 'penjualan.total'],
            [':parent' => 'transaksiPos', ':child' => 'pos.adminlogin'],
            [':parent' => 'transaksiPos', ':child' => 'pos.adminlogout'],
            [':parent' => 'transaksiPos', ':child' => 'pos.caribarang'],
            [':parent' => 'transaksiPos', ':child' => 'pos.cekharga'],
            [':parent' => 'transaksiPos', ':child' => 'pos.ganticustomer'],
            [':parent' => 'transaksiPos', ':child' => 'pos.hapus'],
            [':parent' => 'transaksiPos', ':child' => 'pos.index'],
            [':parent' => 'transaksiPos', ':child' => 'pos.kembalian'],
            [':parent' => 'transaksiPos', ':child' => 'pos.out'],
            [':parent' => 'transaksiPos', ':child' => 'pos.simpan'],
            [':parent' => 'transaksiPos', ':child' => 'pos.suspended'],
            [':parent' => 'transaksiPos', ':child' => 'pos.tambah'],
            [':parent' => 'transaksiPos', ':child' => 'pos.tambahbarang'],
            [':parent' => 'transaksiPos', ':child' => 'pos.ubah'],
            [':parent' => 'transaksiPos', ':child' => 'pos.updatehargamanual'],
            [':parent' => 'transaksiPos', ':child' => 'pos.updateqty'],
            [':parent' => 'customerdisplay', ':child' => 'tools/customerdisplay.getinfo'],
            [':parent' => 'customerdisplay', ':child' => 'tools/customerdisplay.index'],
            [':parent' => 'cekharga', ':child' => 'pos.cekharga'],
            [':parent' => 'cekharga', ':child' => 'tools/cekharga.cekbarcode'],
            [':parent' => 'POS', ':child' => 'transaksiPos'],
            [':parent' => 'POS', ':child' => 'customerdisplay'],
            [':parent' => 'POS', ':child' => 'cekharga'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {

    }

}
