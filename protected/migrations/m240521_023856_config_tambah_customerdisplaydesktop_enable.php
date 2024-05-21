<?php

class m240521_023856_config_tambah_customerdisplaydesktop_enable extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'customerdisplay.pos.enable',
				'nilai'      => '0',
				'deskripsi'  => 'Websocket Client di POS. 0:disable; 1:enable',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m240521_023856_config_tambah_customerdisplaydesktop_enable does not support migration down.\n";
		return false;
	}
}
