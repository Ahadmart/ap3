<?php

class m210211_022528_tambah_barang_restock_min extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->addColumn('barang', 'restock_min', 'INT UNSIGNED NOT NULL DEFAULT 0 AFTER `restock_level`');
	}

	public function safeDown()
	{
		echo "m210211_022528_tambah_barang_restock_min does not support migration down.\n";
		return false;
	}
}
