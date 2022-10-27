<?php

class m221024_025207_authitem_tambah_report_returpenjualan extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'report.returpenjualan', ':tipe' => 0, ':deskripsi' => 'Laporan Retur Penjualan'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'laporanSemua', ':child' => 'report.returpenjualan'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m221024_025207_authitem_tambah_report_returpenjualan does not support migration down.\n";
		return false;
	}
}
