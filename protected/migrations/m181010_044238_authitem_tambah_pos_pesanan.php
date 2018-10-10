<?php

class m181010_044238_authitem_tambah_pos_pesanan extends CDbMigration
{

    public function safeUp()
    {
        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

        $params = [
            [':nama' => 'pos.pesanan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesananubah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesanantambahbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesananupdateqty', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesananbaru', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesanansimpan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.inputpesanan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.pesananprint', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'salesorder.batal', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'salesorder.total', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'transaksiPesananPos', ':tipe' => 1, ':deskripsi' => 'Transaksi Pesanan di POS'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesanan'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesananubah'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesanantambahbarang'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesananupdateqty'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesananbaru'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesanansimpan'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.inputpesanan'],
            [':parent' => 'transaksiPesananPos', ':child' => 'pos.pesananprint'],
            [':parent' => 'transaksiPesananPos', ':child' => 'salesorder.batal'],
            [':parent' => 'transaksiPesananPos', ':child' => 'salesorder.total'],
            [':parent' => 'POS', ':child' => 'transaksiPesananPos'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m181010_044238_authitem_tambah_pos_pesanan does not support migration down.\n";
        return false;
    }

}
