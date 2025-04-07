<?php

class m240821_133405_config_tambah_labelbarangoffset extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'labelbarang.default.offset',
				'nilai'      => '0,0',
				'deskripsi'  => 'Offset (dot) label barang layout default (Kiri,Kanan)',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now,
			]
		);
	}

	public function safeDown()
	{
		echo "m240821_133405_config_tambah_labelbarangoffset does not support migration down.\n";
		return false;
	}
}
