<?php

class m221125_040821_config_tambah_penjualan_hideopentxn extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'penjualan.hideopentxn',
				'nilai'      => '1',
				'deskripsi'  => '0: tidak (normal); 1: sembunyikan penjualan yang kasir masih terbuka ',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);

		// Update nilai default untuk config report.penjualan.hideoepentxn
		$this->update('config', ['nilai' => '1'], 'nama=:nama', [':nama' => 'report.penjualan.hideopentxn']);
	}

	public function safeDown()
	{
		echo "m221125_040821_config_tambah_penjualan_hideopentxn does not support migration down.\n";
		return false;
	}
}
