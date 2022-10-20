<?php

class m221020_041914_menu_tambah_report_harian01 extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 52,
				'root_id'    => 1,
				'nama'       => 'Harian 01',
				'icon'       => '<i class="fa fa-file fa-fw"></i>',
				'link'       => '/report/harian01',
				'keterangan' => 'Laporan Harian 01',
				'level'      => 3,
				'urutan'     => 3,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 96]
		);

	}

	public function safeDown()
	{
		echo "m221020_041914_menu_tambah_report_harian01 does not support migration down.\n";
		return false;
	}
	
}