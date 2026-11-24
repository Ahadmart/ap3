<?php

class m261022_015425_tambah_kolom_penjualan_nomor_publik extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('penjualan', 'nomor_publik', 'VARCHAR(10) NULL AFTER `id`');
		$this->createIndex('nomor_publik_UNIQUE', 'penjualan', 'nomor_publik', true);
	}

	public function safeDown()
	{
		echo "m261022_015425_tambah_kolom_penjualan_nomor_publik does not support migration down.\n";
		return false;
	}
}
