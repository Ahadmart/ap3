<?php

class m240422_091615_menu_update_customerdisplay extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 71,
				'root_id'    => 71,
				'nama'       => 'Customer Display',
				'icon'       => '<i class="fa fa-film fa-fw"></i>',
				'link'       => null,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
			],
			'id=:id',
			[':id' => 75]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 75,
				'root_id'    => 71,
				'nama'       => 'Customer Display Desktop',
				'icon'       => '<i class="fa fa-tv fa-fw"></i>',
				'link'       => 'tools/customerdisplay/desktop',
				'keterangan' => 'Tampilan untuk pelanggan ukuran besar',
				'level'      => 2,
				'urutan'     => 1,
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
				'parent_id'  => 75,
				'root_id'    => 71,
				'nama'       => 'Customer Display Mobile',
				'icon'       => '<i class="fa fa-mobile fa-fw"></i>',
				'link'       => 'tools/customerdisplay/mobile',
				'keterangan' => 'Tampilan untuk pelanggan ukuran kecil',
				'level'      => 2,
				'urutan'     => 2,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 99]
		);
		$this->update(
			'menu',
			[
				'icon'       => '<i class="fa fa-film fa-fw"></i>',
				'link'       => null,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
			],
			'id=:id',
			[':id' => 70]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 70,
				'root_id'    => 1,
				'nama'       => 'Customer Display Desktop',
				'icon'       => '<i class="fa fa-tv fa-fw"></i>',
				'link'       => 'tools/customerdisplay/desktop',
				'keterangan' => 'Tampilan untuk pelanggan ukuran besar',
				'level'      => 3,
				'urutan'     => 1,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 100]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 70,
				'root_id'    => 1,
				'nama'       => 'Customer Display Mobile',
				'icon'       => '<i class="fa fa-mobile fa-fw"></i>',
				'link'       => 'tools/customerdisplay/mobile',
				'keterangan' => 'Tampilan untuk pelanggan ukuran kecil',
				'level'      => 3,
				'urutan'     => 2,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 101]
		);
	}

	public function safeDown()
	{
		echo "m240422_091615_menu_update_customerdisplay does not support migration down.\n";
		return false;
	}
}
