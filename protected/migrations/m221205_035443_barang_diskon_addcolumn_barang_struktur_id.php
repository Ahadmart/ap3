<?php

class m221205_035443_barang_diskon_addcolumn_barang_struktur_id extends CDbMigration
{
    public function safeUp()
    {
        $this->addColumn('barang_diskon', 'barang_struktur_id', 'INT(10) UNSIGNED NULL AFTER `barang_kategori_id`');
        $this->createIndex('fk_barang_diskon_barang_struktur_idx', 'barang_diskon', 'barang_struktur_id');
        $this->addForeignKey('fk_barang_diskon_barang_struktur', 'barang_diskon', 'barang_struktur_id', 'barang_struktur', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m221205_035443_barang_diskon_addcolumn_barang_struktur_id does not support migration down.\n";
        return false;
    }
}
