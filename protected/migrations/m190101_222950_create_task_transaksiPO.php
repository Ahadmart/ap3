<?php

class m190101_222950_create_task_transaksiPO extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $sql    = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";
        $params = [
            [':nama' => 'po.view', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.tambah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.ubah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.hapus', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.index', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.ambilprofil', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.getbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.tambahbarangbaru', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.tambahbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.updateqty', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.total', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.hapusdetail', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.simpan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.print', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.beli', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.ambilpls', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.caribarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.setorder', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.unsetorder', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.inputorder', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.ambiltotal', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.ordersemua', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'po.unordersemua', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'transaksiPO', ':tipe' => 1, ':deskripsi' => 'Transaksi PO'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiPO', ':child' => 'po.view'],
            [':parent' => 'transaksiPO', ':child' => 'po.tambah'],
            [':parent' => 'transaksiPO', ':child' => 'po.ubah'],
            [':parent' => 'transaksiPO', ':child' => 'po.hapus'],
            [':parent' => 'transaksiPO', ':child' => 'po.index'],
            [':parent' => 'transaksiPO', ':child' => 'po.ambilprofil'],
            [':parent' => 'transaksiPO', ':child' => 'po.getbarang'],
            [':parent' => 'transaksiPO', ':child' => 'po.tambahbarangbaru'],
            [':parent' => 'transaksiPO', ':child' => 'po.tambahbarang'],
            [':parent' => 'transaksiPO', ':child' => 'po.updateqty'],
            [':parent' => 'transaksiPO', ':child' => 'po.total'],
            [':parent' => 'transaksiPO', ':child' => 'po.hapusdetail'],
            [':parent' => 'transaksiPO', ':child' => 'po.simpan'],
            [':parent' => 'transaksiPO', ':child' => 'po.print'],
            [':parent' => 'transaksiPO', ':child' => 'po.beli'],
            [':parent' => 'transaksiPO', ':child' => 'po.ambilpls'],
            [':parent' => 'transaksiPO', ':child' => 'po.caribarang'],
            [':parent' => 'transaksiPO', ':child' => 'po.setorder'],
            [':parent' => 'transaksiPO', ':child' => 'po.unsetorder'],
            [':parent' => 'transaksiPO', ':child' => 'po.inputorder'],
            [':parent' => 'transaksiPO', ':child' => 'po.ambiltotal'],
            [':parent' => 'transaksiPO', ':child' => 'po.ordersemua'],
            [':parent' => 'transaksiPO', ':child' => 'po.unordersemua'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m190101_222950_create_task_transaksiPO does not support migration down.\n";
        return false;
    }

}
