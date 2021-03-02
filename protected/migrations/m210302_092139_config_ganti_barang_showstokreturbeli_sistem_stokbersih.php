<?php

class m210302_092139_config_ganti_barang_showstokreturbeli_sistem_stokbersih extends CDbMigration
{
	public function safeUp()
	{
		$this->update('config', [
			'nama' => 'sistem.stokbersih',
			'deskripsi' => '1: retur beli ada status posted, sebelum piutang, mengurangi stok, tapi terhitung di total stok',
			'nilai' => 1
		], 'nama=:param', [':param' => 'barang.showstokreturbeli']);
	}

	public function safeDown()
	{
		echo "m210302_092139_config_ganti_barang_showstokreturbeli_sistem_stokbersih does not support migration down.\n";
		return false;
	}
}
