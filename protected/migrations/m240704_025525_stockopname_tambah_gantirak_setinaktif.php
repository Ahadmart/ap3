<?php

class m240704_025525_stockopname_tambah_gantirak_setinaktif extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn(
			'stock_opname_detail',
			'set_inaktif',
			'TINYINT(1) NOT NULL DEFAULT 0 AFTER `qty_sebenarnya`'
		);
		$this->addColumn(
			'stock_opname_detail',
			'ganti_rak_id',
			'INT UNSIGNED NULL AFTER `set_inaktif`'
		);
		$this->addForeignKey(
			'fk_stock_opname_detail_gantirak',
			'stock_opname_detail',
			'ganti_rak_id',
			'barang_rak',
			'id',
			'NO ACTION',
			'NO ACTION'
		);
		$this->createIndex('fk_stock_opname_detail_gantirak_idx', 'stock_opname_detail', 'ganti_rak_id');
	}

	public function safeDown()
	{
		echo "m240704_025525_stockopname_tambah_gantirak_setinaktif does not support migration down.\n";
		return false;
	}
}
