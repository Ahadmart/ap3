<?php

class m190813_073646_fitur_diskon_perkategori extends CDbMigration
{

    public function safeUp()
    {
        $this->addColumn('barang_diskon', 'barang_kategori_id', 'INT(10) UNSIGNED NULL AFTER `tipe_diskon_id`');
        $this->createIndex('fk_barang_diskon_barang_kategori_idx', 'barang_diskon', 'barang_kategori_id');
        $this->addForeignKey('fk_barang_diskon_barang_kategori', 'barang_diskon', 'barang_kategori_id', 'barang_kategori', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m190813_073646_fitur_diskon_perkategori does not support migration down.\n";
        return false;
    }

}
