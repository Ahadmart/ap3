<?php

class m161026_231135_tambah_theme_biru extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('theme', ['nama' => 'biru', 'deskripsi' => 'Biru Terang', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s')]);
    }

    public function safeDown()
    {
        
    }

}
