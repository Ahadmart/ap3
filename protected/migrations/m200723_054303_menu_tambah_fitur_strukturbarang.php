<?php

class m200723_054303_menu_tambah_fitur_strukturbarang extends CDbMigration
{
	public function safeUp()
	{
		// Sesuaikan urutan menu Config Barang
		$sql = '
		UPDATE menu SET urutan = urutan + 1 WHERE parent_id = 11 AND urutan > 3';
		$this->execute($sql);

		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 11,
				'root_id'    => 1,
				'nama'       => 'Struktur',
				'icon'       => '<i class="fa fa-object-group fa-fw"></i>',
				'link'       => '/strukturbarang/index',
				'keterangan' => 'Struktur Barang 3 Level',
				'level'      => 3,
				'urutan'     => 4,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1
			],
			'id=:id',
			[':id' => 87]
		);

		// Sesuaikan urutan menu Laporan - Penjualan
		$sql = '
		UPDATE menu SET urutan = urutan + 1 WHERE parent_id = 11 AND urutan > 3';
		$this->execute($sql);
		$this->update(
			'menu',
			[
				'parent_id'  => 48,
				'root_id'    => 1,
				'nama'       => 'Penjualan per Struktur',
				'icon'       => '<i class="fa fa-object-group fa-fw"></i>',
				'link'       => '/report/penjualanperstruktur',
				'keterangan' => 'Laporan Penjualan per Struktur',
				'level'      => 3,
				'urutan'     => 4,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 88]
		);
	}

	public function safeDown()
	{
		echo "m200723_054303_menu_tambah_fitur_strukturbarang does not support migration down.\n";
		return false;
	}
}
