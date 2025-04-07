<?php

class m240124_152519_po_detail_tambah_qty_butuh extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('po_detail', 'qty_butuh', 'INT UNSIGNED NULL AFTER `restock_min`');
	}

	public function safeDown()
	{
		echo "m240124_152519_po_detail_tambah_qty_butuh does not support migration down.\n";
		return false;
	}
}
