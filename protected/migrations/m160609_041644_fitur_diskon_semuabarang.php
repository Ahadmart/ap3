<?php

class m160609_041644_fitur_diskon_semuabarang extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->dropForeignKey('fk_barang_diskon_barang', 'barang_diskon');
        $this->alterColumn('barang_diskon', 'barang_id', 'INT(10) UNSIGNED NULL');
        $this->addColumn('barang_diskon', 'semua_barang', "TINYINT NOT NULL DEFAULT 0 COMMENT 'perbarang = 0; semua = 1' AFTER `id`");

        $this->addForeignKey('fk_barang_diskon_barang', 'barang_diskon', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {

    }

}
