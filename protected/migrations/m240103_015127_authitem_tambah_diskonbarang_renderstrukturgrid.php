<?php

class m240103_015127_authitem_tambah_diskonbarang_renderstrukturgrid extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'diskonbarang.renderstrukturgrid', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'configDiskon', ':child' => 'diskonbarang.renderstrukturgrid'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m240103_015127_authitem_tambah_diskonbarang_renderstrukturgrid does not support migration down.\n";
		return false;
	}
}
