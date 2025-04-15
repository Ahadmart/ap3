<?php

class m250415_031944_penjualan_h_tambah_alasan extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->addColumn('penjualan_detail_h', 'alasan', 'VARCHAR(1000) NULL AFTER `waktu`');
	}

	public function safeDown()
	{
		echo "m250415_031944_penjualan_h_tambah_alasan does not support migration down.\n";
		return false;
	}
}
