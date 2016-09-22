<?php

class m160816_012732_tambah_config_pembelian_caribarang extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('config', array('nama' => 'pembelian.caribarangmode', 'nilai' => '1', 'deskripsi' => 'Mode Cari Barang. 0:AutoComplete; 1:Table; 2: Double DropDown', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'));
    }

    public function safeDown()
    {
        echo "m160816_012732_tambah_config_pembelian_caribarang does not support migration down.\n";
        return false;
    }

}
