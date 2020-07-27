<?php

class m200721_025947_barang_tambah_struktur_barang extends CDbMigration
{

    public function safeUp()
    {
        /* Menyesuaikan dengan mysql > 5.7 */
        $this->alterColumn('barang', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");

        /* Ubah barang.kategori_id menjadi (allow) null */
        $this->dropForeignKey('fk_barang_kategori', 'barang');
        $this->alterColumn('barang', 'kategori_id', ' INT(10) UNSIGNED NULL');
        $this->addForeignKey('fk_barang_kategori', 'barang', 'kategori_id', 'barang_kategori', 'id');

        /* Tambah kolom barang.struktur_barang_id */
        $this->addColumn('barang', 'struktur_id', 'INT UNSIGNED NULL AFTER `nama`');
        $this->createIndex('fk_barang_struktur_idx', 'barang', 'struktur_id');
        $this->addForeignKey('fk_barang_struktur', 'barang', 'struktur_id', 'barang_struktur', 'id');
    }

    public function safeDown()
    {
        echo "m200721_025947_barang_tambah_struktur_barang does not support migration down.\n";
        return false;
    }

}
