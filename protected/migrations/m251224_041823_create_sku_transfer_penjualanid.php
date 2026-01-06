<?php

class m251224_041823_create_sku_transfer_penjualanid extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('sku_transfer', 'penjualan_id', 'INT UNSIGNED NULL AFTER `keterangan`');
		$this->createIndex('fk_sku_transfer_penjualan_idx', 'sku_transfer', 'penjualan_id');

		$this->addForeignKey('fk_sku_transfer_penjualan', 'sku_transfer', 'penjualan_id', 'penjualan', 'id');
	}

	public function safeDown()
	{
		echo "m251224_041823_create_sku_transfer_penjualanid does not support migration down.\n";
		return false;
	}
}
