<?php

class m250423_020023_config_tambah_penjualan_hapus_alasan extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'pos.alasanhapusnota',
				'nilai'      => '1',
				'deskripsi'  => 'Hapus nota harus dengan alasan. 0:disable; 1:enable',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now,
			]
		);
		$this->insert(
			'config',
			[
				'nama'       => 'pos.alasanhapusdetail',
				'nilai'      => '1',
				'deskripsi'  => 'Hapus detail harus dengan alasan. 0:disable; 1:enable',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now,
			]
		);
	}

	public function safeDown()
	{
		echo "m250423_020023_config_tambah_penjualan_hapus_alasan does not support migration down.\n";
		return false;
	}
}
