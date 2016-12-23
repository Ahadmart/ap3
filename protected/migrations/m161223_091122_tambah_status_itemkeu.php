<?php

class m161223_091122_tambah_status_itemkeu extends CDbMigration
{

    public function safeUp()
    {
        /* Penyesuaian agar compatible dengan mysql 5.7 */
        $this->alterColumn('item_keuangan', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");

        /* Tambah Field */
        $this->addColumn('item_keuangan', 'status', "TINYINT(1) NOT NULL DEFAULT '1' COMMENT '0=tidak aktif; 1=aktif;' AFTER `jenis`");
    }

    public function safeDown()
    {
        
    }

}
