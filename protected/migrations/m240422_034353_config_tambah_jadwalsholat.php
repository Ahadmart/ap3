<?php

class m240422_034353_config_tambah_jadwalsholat extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->insert(
			'config',
			[
				'nama'       => 'jadwalsholat.koordinat',
				'nilai'      => '-6.20199;106.829',
				'deskripsi'  => 'Koordinat Geografis (Latitude;Longitude)',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
		$this->insert(
			'config',
			[
				'nama'       => 'jadwalsholat.offset',
				'nilai'      => '3,3,-3,3,3,3,3,3',
				'deskripsi'  => 'Offset (menit) waktu (Imsak,Fajr,Sunrise,Dhuhr,Asr,Maghrib,Sunset,Isha,Midnight)',
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			]
		);
	}

	public function safeDown()
	{
		echo "m240422_034353_config_tambah_jadwalsholat does not support migration down.\n";
		return false;
	}
}
