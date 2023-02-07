<?php

class m221226_101940_kasir_tambahkolom_total_koincashback_dipakai extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('kasir', 'total_koincashback_dipakai', 'DECIMAL(18,2) NULL AFTER `total_tarik_tunai`');
	}

	public function safeDown()
	{
		echo "m221226_101940_kasir_tambahkolom_total_koincashback_dipakai does not support migration down.\n";
		return false;
	}
}
