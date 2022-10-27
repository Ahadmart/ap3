<?php

class m221020_000400_authitem_report_harian01 extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'report.harian01', ':tipe' => 0, ':deskripsi' => 'Laporan Harian'],
			[':nama' => 'report.printharian01', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'laporanSemua', ':child' => 'report.harian01'],
			[':parent' => 'laporanSemua', ':child' => 'report.printharian01'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m221020_000400_authitem_report_harian01 does not support migration down.\n";
		return false;
	}
}
