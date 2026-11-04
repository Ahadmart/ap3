<?php

class m261104_022511_authitem_tambah_laporanperbarangperrak extends CDbMigration
{
	public function safeUp() {

		$sql = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'report.penjualanperbarangperrak', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'report.printpenjualanrak', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'laporanSemua', ':child' => 'report.penjualanperbarangperrak'],
			[':parent' => 'laporanSemua', ':child' => 'report.printpenjualanrak'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m261104_022511_authitem_tambah_laporanperbarangperrak does not support migration down.\n";
		return false;
	}
}
