<?php

class m210311_072002_config_hapus_sistem_stokbersih extends CDbMigration
{
	public function safeUp()
	{
		$this->delete('config', 'nama=:nama', [':nama' => 'sistem.stokbersih']);
	}

	public function safeDown()
	{
		echo "m210311_072002_config_hapus_sistem_stokbersih does not support migration down.\n";
		return false;
	}
}
