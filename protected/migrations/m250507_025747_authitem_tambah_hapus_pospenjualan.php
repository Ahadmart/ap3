<?php

class m250507_025747_authitem_tambah_hapus_pospenjualan extends CDbMigration
{
    public function safeUp()
    {
        $sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
        $params = [
            [':nama' => 'penjualan.hapusdetailkonfirmasi', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'pos.hapusdetail', ':tipe' => 0, ':deskripsi' => ''],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
        $params = [
            [':parent' => 'transaksiPenjualan', ':child' => 'penjualan.hapusdetailkonfirmasi'],
            [':parent' => 'transaksiPos', ':child' => 'pos.hapusdetail'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m250507_025747_authitem_tambah_hapus_pospenjualan does not support migration down.\n";
        return false;
    }
}
