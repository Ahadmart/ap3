<?php

class m210310_063108_inventoryb_tambah_retur_pembelian_posted_id extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('inventory_balance', 'retur_pembelian_detail_id', 'INT(10) UNSIGNED NULL AFTER `stock_opname_detail_id`');
		$this->createIndex('fk_inventory_balance_returbelidetail_idx', 'inventory_balance', 'retur_pembelian_detail_id');
		$this->addForeignKey('fk_inventory_balance_returbelidetail', 'inventory_balance', 'retur_pembelian_detail_id', 'retur_pembelian_detail', 'id', 'NO ACTION', 'NO ACTION');
	}

	public function safeDown()
	{
		echo "m210310_063108_inventoryb_tambah_retur_pembelian_posted_id does not support migration down.\n";
		return false;
	}
}
