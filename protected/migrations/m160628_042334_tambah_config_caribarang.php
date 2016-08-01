<?php

class m160628_042334_tambah_config_caribarang extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('config', array('nama' => 'pos.caribarangmode', 'nilai' => '0', 'deskripsi' => 'Mode Cari Barang. 0:AutoComplete; 1:Table', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'));
    }

    public function safeDown()
    {
        echo "m160628_042334_tambah_config_caribarang does not support migration down.\n";
        return false;
    }

}
