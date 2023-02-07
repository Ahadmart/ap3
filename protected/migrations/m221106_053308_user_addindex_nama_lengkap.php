<?php

class m221106_053308_user_addindex_nama_lengkap extends CDbMigration
{
	public function safeUp()
	{
		$this->execute('ALTER TABLE user ADD FULLTEXT INDEX `nama_lengkap_idx` (`nama_lengkap`)');
	}

	public function safeDown()
	{
		echo "m221106_053308_user_addindex_nama_lengkap does not support migration down.\n";
		return false;
	}
}
