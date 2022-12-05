<?php

class m221205_013449_config_tambah_penjualan_hideTotalMarginOpenTxn extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'penjualan.hideTotalMarginOpenTxn',
				'nilai'      => '1',
				'deskripsi'  => '0: Tidak (show all); 1: Sembunyikan kolom total dan margin jika kasir masih ada yang terbuka ',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m221205_013449_config_tambah_penjualan_hideTotalMarginOpenTxn does not support migration down.\n";
		return false;
	}
	
}