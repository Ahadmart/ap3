<?php

class m160614_060405_add_index_nama_barang extends CDbMigration
{

    public function safeUp()
    {
        $this->createIndex('nama_barang_idx', 'barang', 'nama');
    }

    public function safeDown()
    {
		echo "m160614_060405_add_index_nama_barang does not support migration down.\n";
		return false;

    }

}
