<?php

class m231011_020020_profil_tambah_npwp extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->addColumn('profil', 'npwp', "VARCHAR(16) NULL AFTER `surel`");
	}

	public function safeDown()
	{
		echo "m231011_020020_profil_tambah_npwp does not support migration down.\n";
		return false;
	}
}
