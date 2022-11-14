<?php

class m221114_001004_config_tambah_reportpenjualan_hideopentxn extends CDbMigration
{
	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');
        $this->insert(
            'config',
            [
                'nama'       => 'report.penjualan.hideopentxn',
                'nilai'      => '0',
                'deskripsi'  => '0: tidak (normal); 1: sembunyikan penjualan yang kasir masih terbuka ',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );
	}

	public function safeDown()
	{
		echo "m221114_001004_config_tambah_reportpenjualan_hideopentxn does not support migration down.\n";
		return false;
	}
	
}