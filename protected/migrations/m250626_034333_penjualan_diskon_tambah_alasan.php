<?php

class m250626_034333_penjualan_diskon_tambah_alasan extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('penjualan_diskon', 'alasan', 'VARCHAR(1000) NULL AFTER `tipe_diskon_id`');
	}

	public function safeDown()
	{
		echo "m250626_034333_penjualan_diskon_tambah_alasan does not support migration down.\n";
		return false;
	}
}
