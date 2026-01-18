<?php

class m250701_035859_authitem_tambah_konfirmasiupdateharga extends CDbMigration
{

	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'pos.konfirmasiupdateharga', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiPos', ':child' => 'pos.konfirmasiupdateharga'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m250701_035859_authitem_tambah_konfirmasiupdateharga does not support migration down.\n";
		return false;
	}
}
