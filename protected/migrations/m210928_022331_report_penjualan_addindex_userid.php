<?php

class m210928_022331_report_penjualan_addindex_userid extends CDbMigration
{
	public function safeUp()
	{
		$this->createIndex('user_idx', 'report_penjualan', 'user_id', false);
	}

	public function safeDown()
	{
		echo "m210928_022331_report_penjualan_addindex_userid does not support migration down.\n";
		return false;
	}
	
}