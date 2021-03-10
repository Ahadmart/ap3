<?php

class m210310_063108_inventoryb_tambah_retur_pembelian_posted_id extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('inventory_balance', 'retur_pembelian_detail_id', 'INT(10) UNSIGNED NULL AFTER `stock_opname_detail_id`');
	}

	public function safeDown()
	{
		echo "m210310_063108_inventoryb_tambah_retur_pembelian_posted_id does not support migration down.\n";
		return false;
	}
}
