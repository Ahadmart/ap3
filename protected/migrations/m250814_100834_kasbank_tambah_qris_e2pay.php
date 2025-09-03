<?php

class m250814_100834_kasbank_tambah_qris_e2pay extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->execute("INSERT IGNORE INTO kas_bank (nama, updated_at, updated_by, created_at) VALUES ('QRIS-D', '{$now}', 1, '{$now}')");
	}

	public function safeDown()
	{
		echo "m250814_100834_kasbank_tambah_qris_e2pay does not support migration down.\n";
		return false;
	}
}
