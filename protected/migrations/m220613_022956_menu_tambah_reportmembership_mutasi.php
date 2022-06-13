<?php

class m220613_022956_menu_tambah_reportmembership_mutasi extends CDbMigration
{

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		// Sesuaikan urutan menu Laporan
		$sql = '
		UPDATE menu SET urutan = urutan + 1 WHERE parent_id = 7 and root_id = 1 AND urutan >= 6';
		$this->execute($sql);
		$this->update(
			'menu',
			[
				'parent_id'  => 7,
				'root_id'    => 1,
				'nama'       => 'Membership Online',
				'level'      => 2,
				'urutan'     => 6,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 92]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 92,
				'root_id'    => 1,
				'nama'       => 'Mutasi Poin',
				'icon'       => '<i class="fa fa-star fa-fw"></i>',
				'link'       => '/report/mutasipoin',
				'keterangan' => 'Laporan Mutasi Poin Membership Online',
				'level'      => 3,
				'urutan'     => 1,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 93]
		);
		$this->update(
			'menu',
			[
				'parent_id'  => 92,
				'root_id'    => 1,
				'nama'       => 'Mutasi Koin',
				'icon'       => '<i class="fa fa-dot-circle-o fa-fw"></i>',
				'link'       => '/report/mutasikoin',
				'keterangan' => 'Laporan Mutasi Koin Membership Online',
				'level'      => 3,
				'urutan'     => 2,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 94]
		);
	}

	public function safeDown()
	{
		echo "m220613_022956_menu_tambah_reportmembership_mutasi does not support migration down.\n";
		return false;
	}
}
