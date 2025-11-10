<?php

class m251105_032103_create_index_tanggal extends CDbMigration
{
	public function safeUp()
	{
		$this->createIndex('stock_opname_tangal_idx', 'stock_opname', 'tanggal');
	}

	public function safeDown()
	{
		echo "m251105_032103_create_index_tanggal does not support migration down.\n";
		return false;
	}
}
