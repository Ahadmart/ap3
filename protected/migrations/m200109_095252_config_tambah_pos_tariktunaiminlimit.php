<?php

class m200109_095252_config_tambah_pos_tariktunaiminlimit extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
                    'nama'       => 'pos.tariktunaiminlimit',
                    'nilai'      => '25000',
                    'deskripsi'  => 'Limit Belanja Minimum Untuk Bisa Tarik Tunai',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m200109_095252_config_tambah_pos_tariktunaiminlimit does not support migration down.\n";
        return false;
    }

}
