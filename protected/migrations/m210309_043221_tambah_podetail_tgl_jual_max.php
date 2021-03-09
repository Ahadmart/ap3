<?php

class m210309_043221_tambah_podetail_tgl_jual_max extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('po_detail', 'tgl_jual_max', 'DATETIME NULL AFTER `qty_order`');
	}

	public function safeDown()
	{
		echo "m210309_043221_tambah_podetail_tgl_jual_max does not support migration down.\n";
		return false;
	}
}
