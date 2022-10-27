<?php

class m220823_004545_authitem_tambah_report extends CDbMigration
{
    public function safeUp()
    {
        $sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
        $params = [
            [':nama' => 'report.printdiskon', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.printrekapdiskon', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.mutasipoin', ':tipe' => 0, ':deskripsi' => 'Report mutasi poin untuk member online'],
            [':nama' => 'report.mutasikoin', ':tipe' => 0, ':deskripsi' => 'Report mutasi koin untuk member online'],
            [':nama' => 'report.printpembelian', ':tipe' => 0, ':deskripsi' => ''],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
        $params = [
            [':parent' => 'laporanSemua', ':child' => 'report.printdiskon'],
            [':parent' => 'laporanSemua', ':child' => 'report.printrekapdiskon'],
            [':parent' => 'laporanSemua', ':child' => 'report.mutasipoin'],
            [':parent' => 'laporanSemua', ':child' => 'report.mutasikoin'],
            [':parent' => 'laporanSemua', ':child' => 'report.printpembelian'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m220823_004545_authitem_tambah_report does not support migration down.\n";
        return false;
    }
}
