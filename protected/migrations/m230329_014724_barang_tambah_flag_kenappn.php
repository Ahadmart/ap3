<?php

class m230329_014724_barang_tambah_flag_kenappn extends CDbMigration
{

	public function safeUp()
	{
		$this->addColumn('barang', 'kena_ppn', "TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: Tidak dikenakan ppn; 1: Kena ppn' AFTER `variant_coefficient`");
	}

	public function safeDown()
	{
		echo "m230329_014724_barang_tambah_flag_kenappn does not support migration down.\n";
		return false;
	}
}
