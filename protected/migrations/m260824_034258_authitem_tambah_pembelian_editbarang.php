<?php

class m260824_034258_authitem_tambah_pembelian_editbarang extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'pembelian.getdetailbarang', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'pembelian.simpaneditbarang', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiPembelian', ':child' => 'pembelian.getdetailbarang'],
			[':parent' => 'transaksiPembelian', ':child' => 'pembelian.simpaneditbarang'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

	}

	public function safeDown()
	{
		echo "m260824_034258_authitem_tambah_pembelian_editbarang does not support migration down.\n";
		return false;
	}
}