<?php

class m240422_103553_authitem_ubah_customerdisplay extends CDbMigration
{
	public function safeUp()
	{
		$this->delete('AuthItemChild', 'parent=:parent AND child=:child', [
			':parent' => 'customerdisplay',
			':child'  => 'tools/customerdisplay.index',
		]);

		$this->delete('AuthItem', 'name=:name', [
			':name' => 'tools/customerdisplay.index',
		]);

		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'tools/customerdisplay.mobile', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/customerdisplay.desktop', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'customerdisplay', ':child' => 'tools/customerdisplay.mobile'],
			[':parent' => 'customerdisplay', ':child' => 'tools/customerdisplay.desktop'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m240422_103553_authitem_ubah_customerdisplay does not support migration down.\n";
		return false;
	}
}
