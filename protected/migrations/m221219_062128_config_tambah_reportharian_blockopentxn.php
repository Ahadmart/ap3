<?php

class m221219_062128_config_tambah_reportharian_blockopentxn extends CDbMigration
{
	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');
        $this->insert(
            'config',
            [
                'nama'       => 'report.harian.blockopentxn',
                'nilai'      => '1',
                'deskripsi'  => '0: Tidak; 1: Blok report harian, jika kasir masih buka',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ]
        );
	}

	public function safeDown()
	{
		echo "m221219_062128_config_tambah_reportharian_blockopentxn does not support migration down.\n";
		return false;
	}
	
}