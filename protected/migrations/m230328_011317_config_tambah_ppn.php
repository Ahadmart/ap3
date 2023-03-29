<?php

class m230328_011317_config_tambah_ppn extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'ppn.penjualan',
				'nilai'      => '11',
				'deskripsi'  => 'PPN penjualan dalam persen (%)',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m230328_011317_config_tambah_ppn does not support migration down.\n";
		return false;
	}
}
