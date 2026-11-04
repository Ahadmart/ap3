<?php

class m261104_012110_menu_tambah_laporan_penjualan_perbarang_perrak extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');

		// Atur urutandi menu laporan penjualan
		$sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 2
		WHERE
			parent_id = 48 and urutan >= 5
		';
		$this->execute($sql);

		$sql = '
		UPDATE menu 
		SET 
			urutan = 6
		WHERE
			id = 56
		';
		$this->execute($sql);

		$this->update('menu',
			[
				'parent_id' => 48,
				'root_id' => 1,
				'nama' => 'Penjualan per Barang per Rak',
				'icon' => '<i class="fa fa-server fa-fw"></i>',
				'link' => '/report/penjualanperbarangperrak',
				'keterangan' => 'Laporan Penjualan per Item per Rak',
				'level' => 3,
				'urutan' => 5,
				'status' => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			], 'id=:id', [':id' => 109]);
	}

	public function safeDown()
	{
		echo "m261104_012110_menu_tambah_laporan_penjualan_perbarang_perrak does not support migration down.\n";
		return false;
	}
}
