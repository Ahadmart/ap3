<?php

class m190103_013539_tambah_item_otorisasi_tools_cekharga_caribarang extends CDbMigration
{

    public function safeUp()
    {
        $sql    = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";
        $params = [
            [':nama' => 'tools/cekharga.caribarang', ':tipe' => 0, ':deskripsi' => ''],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
        
        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'cekharga', ':child' => 'tools/cekharga.caribarang'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m190103_013539_tambah_item_otorisasi_tools_cekharga_caribarang does not support migration down.\n";
        return false;
    }

}
