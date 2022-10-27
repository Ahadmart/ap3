<?php

class m220822_064457_menu_tambah_report_pembelian_pernota extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		// Sesuaikan urutan menu Laporan
		$sql = '
		UPDATE menu SET urutan = urutan + 1 WHERE parent_id = 49 and root_id = 1 AND urutan = 1';
		$this->execute($sql);
		$this->update(
			'menu',
			[
				'parent_id'  => 49,
				'root_id'    => 1,
				'nama'       => 'Pembelian per Nota',
				'icon'       => '<i class="fa fa-truck fa-fw"></i>',
				'link'       => '/report/pembelian',
				'keterangan' => 'Laporan Pembelian per Nota',
				'level'      => 3,
				'urutan'     => 1,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 95]
		);
	}

	public function safeDown()
	{
		echo "m220822_064457_menu_tambah_report_pembelian_pernota does not support migration down.\n";
		return false;
	}
}
