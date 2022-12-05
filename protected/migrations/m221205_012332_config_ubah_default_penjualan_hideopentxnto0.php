<?php

class m221205_012332_config_ubah_default_penjualan_hideopentxnto0 extends CDbMigration
{
	public function safeUp()
	{
		$this->update('config', ['nilai' => 0], "nama='penjualan.hideopentxn'");
	}

	public function safeDown()
	{
		echo "m221205_012332_config_ubah_default_penjualan_hideopentxnto0 does not support migration down.\n";
		return false;
	}
}
