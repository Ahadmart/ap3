<?php

class m230112_094928_barang_diskon_addcolumn_member_online_flag extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('barang_diskon', 'member_online_flag', 'TINYINT UNSIGNED NULL AFTER `tipe_diskon_id`');
	}

	public function safeDown()
	{
		echo "m230112_094928_barang_diskon_addcolumn_member_online_flag does not support migration down.\n";
		return false;
	}
}
