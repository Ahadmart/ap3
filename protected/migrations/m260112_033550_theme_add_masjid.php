<?php

class m260112_033550_theme_add_masjid extends CDbMigration
{
	public function safeUp()
	{
		$this->insert('theme', [
			'nama'       => 'masjid',
			'deskripsi'  => 'Masjid',
			'updated_by' => 1,
		]);
	}

	public function safeDown()
	{
		echo "m260112_033550_theme_add_masjid does not support migration down.\n";
		return false;
	}
}
