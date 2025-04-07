<?php

class m240517_023133_menu_tambah_brosurpromo extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 70,
				'root_id'    => 1,
				'nama'       => 'Brosur Promo',
				'icon'       => '<i class="fa fa-image fa-fw"></i>',
				'link'       => 'tools/brosurpromo',
				'keterangan' => 'Mengelola Gambar brosur untuk program promo',
				'level'      => 3,
				'urutan'     => 3,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 102]
		);
	}

	public function safeDown()
	{
		echo "m240517_023133_menu_tambah_brosurpromo does not support migration down.\n";
		return false;
	}
}