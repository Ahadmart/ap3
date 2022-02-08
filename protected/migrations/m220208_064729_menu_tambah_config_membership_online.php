<?php

class m220208_064729_menu_tambah_config_membership_online extends CDbMigration
{
	public function safeUp()
	{
		$now = date('Y-m-d H:i:s');
		$this->update(
			'menu',
			[
				'parent_id'  => 14,
				'root_id'    => 1,
				'nama'       => 'Membership',
				'icon'       => '<i class="fa fa-vcard-o fa-fw"></i>',
				'link'       => '/membership/index',
				'keterangan' => 'Konfigurasi Ahad Membership (Online)',
				'level'      => 3,
				'urutan'     => 4,
				'status'     => 1,
				'updated_at' => $now,
				'updated_by' => 1,
				'created_at' => $now
			],
			'id=:id',
			[':id' => 89]
		);
	}

	public function safeDown()
	{
		echo "m220208_064729_menu_tambah_config_membership_online does not support migration down.\n";
		return false;
	}
	
}