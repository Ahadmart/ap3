<?php

class m240422_113230_config_tambah_customerdisplay_wsport extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'customerdisplay.wsport',
				'nilai'      => '48080',
				'deskripsi'  => 'Port Websocket untuk Customer Display',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m240422_113230_config_tambah_customerdisplay_wsport does not support migration down.\n";
		return false;
	}
}
