<?php

class m250625_035200_config_tambah_diskonadmin_alasan extends CDbMigration
{
	public function safeUp()
	{

		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'pos.alasandiskonadmin',
				'nilai'      => '1',
				'deskripsi'  => 'Diskon manual/admin harus dengan alasan. 0:disable; 1:enable',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now,
			]
		);
	}

	public function safeDown()
	{
		echo "m250625_035200_config_tambah_diskonadmin_alasan does not support migration down.\n";
		return false;
	}
}
