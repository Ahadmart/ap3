<?php

class m221024_025846_menu_tambah_report_returpenjualan extends CDbMigration
{

	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 48,
				'root_id'    => 1,
				'nama'       => 'Retur Penjualan per Nota',
				'icon'       => '<i class="fa fa-reply fa-fw"></i>',
				'link'       => '/report/returpenjualan',
				'keterangan' => 'Laporan Retur Penjualan per Nota',
				'level'      => 3,
				'urutan'     => 7,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 97]
		);
	}

	public function safeDown()
	{
		echo "m221024_025846_menu_tambah_report_returpenjualan does not support migration down.\n";
		return false;
	}
}
