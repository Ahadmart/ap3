<?php

class m221010_072304_tambah_report_penjualan_nama_user extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('report_penjualan', 'nama_user', 'VARCHAR(100) NULL AFTER `margin`');
	}

	public function safeDown()
	{
		echo "m221010_072304_tambah_report_penjualan_nama_user does not support migration down.\n";
		return false;
	}
}
