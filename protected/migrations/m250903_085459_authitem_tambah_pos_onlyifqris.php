<?php

class m250903_085459_authitem_tambah_pos_onlyifqris extends CDbMigration
{

	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'pos.onlyifqris', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiPos', ':child' => 'pos.onlyifqris'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m250903_085459_authitem_tambah_pos_onlyifqris does not support migration down.\n";
		return false;
	}
	
}