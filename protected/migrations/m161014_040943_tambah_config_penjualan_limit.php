<?php

class m161014_040943_tambah_config_penjualan_limit extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('config', array('nama' => 'penjualan.limit', 'nilai' => '3000000', 'deskripsi' => 'Batas maksimum penjualan (bukan POS) per nota. 0=unlimit', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s')));
    }

    public function safeDown()
    {
        echo "m161014_040943_tambah_config_penjualan_limit does not support migration down.\n";
        return false;
    }

}
