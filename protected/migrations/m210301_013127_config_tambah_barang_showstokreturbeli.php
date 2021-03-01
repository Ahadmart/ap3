<?php

class m210301_013127_config_tambah_barang_showstokreturbeli extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'barang.showstokreturbeli',
				'nilai'      => '0',
				'deskripsi'  => '0: tidak ditampilkan; 1: tampilkan qty retur beli (hanya draft) di barang/index, stockopname/ubah, tools/cetakformso/index, ',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m210301_013127_config_tambah_barang_showstokreturbeli does not support migration down.\n";
		return false;
	}
}
