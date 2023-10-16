<?php

class m231016_042256_menu_tambah_transaksi_ppnpembelian extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		// Sesuaikan urutan menu Transaksi
		$sql = '
		UPDATE menu SET urutan = urutan + 2 WHERE parent_id = 5 and root_id = 1 AND urutan = 16';
		$this->execute($sql);
		$this->update(
			'menu',
			[
				'parent_id'  => 5,
				'root_id'    => 1,
				'nama'       => 'PPN Pembelian',
				'icon'       => '<i class="fa fa-scissors fa-fw"></i>',
				'link'       => '/ppnpembelian/index',
				'keterangan' => 'Input Faktur PPN Pembelian',
				'level'      => 2,
				'urutan'     => 16,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 98]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 5,
				'root_id'    => 1,
				'nama'       => '-',
				'keterangan' => 'Divider',
				'level'      => 2,
				'urutan'     => 17,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 99]
		);
	}

	public function safeDown()
	{
		echo "m231016_042256_menu_tambah_transaksi_ppnpembelian does not support migration down.\n";
		return false;
	}
}
