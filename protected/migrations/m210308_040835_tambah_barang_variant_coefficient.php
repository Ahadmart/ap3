<?php

class m210308_040835_tambah_barang_variant_coefficient extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('barang', 'variant_coefficient', 'INT UNSIGNED NOT NULL DEFAULT 1 AFTER `restock_min`');
	}

	public function safeDown()
	{
		echo "m210308_040835_tambah_barang_variant_coefficient does not support migration down.\n";
		return false;
	}
}
