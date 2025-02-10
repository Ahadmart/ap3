<?php

class m250210_021144_menu_tambah_sku extends CDbMigration
{
	public function safeUp()
	{
		// Geser urutan diskon barang, 2
		$sql = '
		UPDATE menu 
		SET 
			urutan = 11
		WHERE
			id = 22
		';
		$this->execute($sql);

		$now = date('Y-m-d H:i:s');

		$this->update('menu', [
			'parent_id'  => 11,
			'root_id'    => 1,
			'nama'       => '-',
			'keterangan' => 'Divider',
			'level'      => 3,
			'urutan'     => 8,
			'status'     => 1,
			'updated_at' => $now,
			'updated_by' => 1,
			'created_at' => $now
		], 'id=:id', [':id' => 104]);

		$this->update('menu', [
			'parent_id'  => 11,
			'root_id'    => 1,
			'nama'       => 'SKU',
			'icon'       => '<i class="fa fa-barcode fa-fw"></i>',
			'link'       => '/sku/index',
			'keterangan' => 'Stock Keeping Unit',
			'level'      => 3,
			'urutan'     => 9,
			'status'     => 1,
			'updated_at' => $now,
			'updated_by' => 1,
			'created_at' => $now
		], 'id=:id', [':id' => 105]);

		$this->update('menu', [
			'parent_id'  => 11,
			'root_id'    => 1,
			'nama'       => '-',
			'keterangan' => 'Divider',
			'level'      => 3,
			'urutan'     => 10,
			'status'     => 1,
			'updated_at' => $now,
			'updated_by' => 1,
			'created_at' => $now
		], 'id=:id', [':id' => 106]);
	}

	public function safeDown()
	{
		echo "m250210_021144_menu_tambah_sku does not support migration down.\n";
		return false;
	}
}
