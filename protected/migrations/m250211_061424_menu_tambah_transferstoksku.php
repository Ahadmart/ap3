<?php

class m250211_061424_menu_tambah_transferstoksku extends CDbMigration
{
	public function safeUp()
	{

		// Geser urutan data harian, 2
		$sql = '
		UPDATE menu
		SET
			urutan = 18
		WHERE
			id = 47
		';
		$this->execute($sql);

		$now = date('Y-m-d H:i:s');

		$this->update('menu', [
			'parent_id'  => 5,
			'root_id'    => 1,
			'nama'       => 'Transfer Stok SKU',
			'icon'       => '<i class="fa fa-folder-open-o fa-fw"></i>',
			'link'       => '/skutransfer/index',
			'keterangan' => 'Transfer stok barang di dalam SKU',
			'level'      => 2,
			'urutan'     => 16,
			'status'     => 1,
			'updated_at' => $now,
			'updated_by' => 1,
			'created_at' => $now,
		], 'id=:id', [':id' => 107]);

		$this->update('menu', [
			'parent_id'  => 5,
			'root_id'    => 1,
			'nama'       => '-',
			'keterangan' => 'Divider',
			'level'      => 2,
			'urutan'     => 17,
			'status'     => 1,
			'updated_at' => $now,
			'updated_by' => 1,
			'created_at' => $now,
		], 'id=:id', [':id' => 108]);
	}

	public function safeDown()
	{
		echo "m250211_061424_menu_tambah_transferstoksku does not support migration down.\n";
		return false;
	}
}