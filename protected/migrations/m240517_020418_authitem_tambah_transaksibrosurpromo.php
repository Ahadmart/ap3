<?php

class m240517_020418_authitem_tambah_transaksibrosurpromo extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'brosurpromo.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'brosurpromo.uploadbrosur', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'brosurpromo.loadbrosur', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'brosurpromo.hapus', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'transaksiBrosurPromo', ':tipe' => 1, ':deskripsi' => 'Mengelola Gambar-gambar Promo'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiBrosurPromo', ':child' => 'brosurpromo.index'],
			[':parent' => 'transaksiBrosurPromo', ':child' => 'brosurpromo.uploadbrosur'],
			[':parent' => 'transaksiBrosurPromo', ':child' => 'brosurpromo.loadbrosur'],
			[':parent' => 'transaksiBrosurPromo', ':child' => 'brosurpromo.hapus'],
			[':parent' => 'SUPERVISOR', ':child' => 'transaksiBrosurPromo'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m240517_020418_authitem_tambah_transaksibrosurpromo does not support migration down.\n";
		return false;
	}
}
