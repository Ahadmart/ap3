<?php

class m230517_035551_penjualan_detail_tambah_ppn extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('penjualan_detail', 'ppn', "DECIMAL(18,2) NULL DEFAULT 0 COMMENT 'Dalam nominal (IDR)' AFTER `diskon`");
	}

	public function safeDown()
	{
		echo "m230517_035551_penjualan_detail_tambah_ppn does not support migration down.\n";
		return false;
	}
	
}