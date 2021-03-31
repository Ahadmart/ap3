<?php

class m210224_012433_podetail_tambah_restock_min extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('po_detail', 'restock_min', 'INT(10) UNSIGNED NULL AFTER `est_sisa_hari`');
	}

	public function safeDown()
	{
		echo "m210224_012433_podetail_tambah_restock_min does not support migration down.\n";
		return false;
	}
}
