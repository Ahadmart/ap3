<?php

class m250919_014942_authitem_tambah_so_gensampelacak extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'stockopname.gensampelacak', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiSO', ':child' => 'stockopname.gensampelacak'],
			[':parent' => 'transaksiSO-simpan', ':child' => 'stockopname.gensampelacak'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m250919_014942_authitem_tambah_so_gensampelacak does not support migration down.\n";
		return false;
	}
}
