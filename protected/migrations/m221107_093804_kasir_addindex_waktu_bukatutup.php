<?php

class m221107_093804_kasir_addindex_waktu_bukatutup extends CDbMigration
{
	public function safeUp()
	{
		$this->createIndex('waktu_buka_idx', 'kasir', 'waktu_buka');
		$this->createIndex('waktu_tutup_idx', 'kasir', 'waktu_tutup');
	}

	public function safeDown()
	{
		echo "m221107_093804_kasir_addindex_waktu_bukatutup does not support migration down.\n";
		return false;
	}
}
