<?php

class m230112_094928_barang_diskon_addcolumn_member_online_flag_level extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('barang_diskon', 'member_online_level', 'INT UNSIGNED NULL AFTER `tipe_diskon_id`');
		$this->addColumn('barang_diskon', 'member_online_flag', 'TINYINT UNSIGNED NULL AFTER `tipe_diskon_id`');
	}

	public function safeDown()
	{
		echo "m230112_094928_barang_diskon_addcolumn_member_online_flag_level does not support migration down.\n";
		return false;
	}
}
