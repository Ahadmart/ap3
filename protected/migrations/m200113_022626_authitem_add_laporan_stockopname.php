<?php

class m200113_022626_authitem_add_laporan_stockopname extends CDbMigration
{

    public function safeUp()
    {
        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

        $params = [
            [':nama' => 'report.stockopname', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.printstockopname', ':tipe' => 0, ':deskripsi' => ''],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'laporanSemua', ':child' => 'report.stockopname'],
            [':parent' => 'laporanSemua', ':child' => 'report.printstockopname'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m200113_022626_authitem_add_laporan_stockopname does not support migration down.\n";
        return false;
    }

}
