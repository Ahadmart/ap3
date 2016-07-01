<?php

class m160701_033907_tambah_theme_hijau extends CDbMigration
{

    public function safeUp()
    {
        $this->insert('theme', ['nama' => 'hijau', 'deskripsi' => 'Hijau AhadPOS2', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00']);
    }

    public function safeDown()
    {

    }

}
