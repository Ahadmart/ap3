<?php

class m190213_031734_tambah_config_pos_modeadmin extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
            'nama'       => 'pos.modeadmin',
            'nilai'      => '0',
            'deskripsi'  => 'Mode Admin Always ON (relogin required); 0=false; 1=true',
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m190213_031734_tambah_config_pos_modeadmin does not support migration down.\n";
        return false;
    }

}
