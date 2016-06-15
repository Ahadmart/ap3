<?php

class m160615_033622_update_daftar_theme extends CDbMigration
{

    public function safeUp()
    {
        $this->update('theme', ['nama' => 'orange', 'deskripsi' => 'Orange'], "nama = 'materialize'");
    }

    public function safeDown()
    {

    }

}
