<?php

class m211002_012712_report_penjualan_createpk extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('report_penjualan', 'id', 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST');
	}

	public function safeDown()
	{
		echo "m211002_012712_report_penjualan_createpk does not support migration down.\n";
		return false;
	}
}
