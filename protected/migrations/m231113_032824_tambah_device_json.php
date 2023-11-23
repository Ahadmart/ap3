<?php

class m231113_032824_tambah_device_json extends CDbMigration
{
	public function safeUp()
	{
		$this->insert('device', ['tipe_id' => Device::TIPE_JSON_FILE, 'nama' => 'JSON', 'keterangan' => 'Export to JSON', 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s')]);
	}

	public function safeDown()
	{
		echo "m231113_032824_tambah_device_json does not support migration down.\n";
		return false;
	}
}
