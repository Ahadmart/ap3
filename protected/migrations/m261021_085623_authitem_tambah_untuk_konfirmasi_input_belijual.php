<?php

class m261021_085623_authitem_tambah_untuk_konfirmasi_input_belijual extends CDbMigration
{
	public function safeUp()
	{
		$sql = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'pembelian.belinaikjualtetap', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'penjualan.marginminus', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'transaksiPembelian', ':child' => 'pembelian.belinaikjualtetap'],
			[':parent' => 'transaksiPenjualan', ':child' => 'penjualan.marginminus'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m261021_085623_authitem_tambah_untuk_konfirmasi_input_belijual does not support migration down.\n";
		return false;
	}
}
