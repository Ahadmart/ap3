<?php

class m231012_083233_pembelianppn_tambah_npwp extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->addColumn('pembelian_ppn', 'npwp', "VARCHAR(16) NULL AFTER `pembelian_id`");
	}

	public function safeDown()
	{
		echo "m231012_083233_pembelianppn_tambah_npwp does not support migration down.\n";
		return false;
	}
}
