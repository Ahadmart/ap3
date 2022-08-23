<?php

class m220823_010403_authitem_membershiponline extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'membership.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.registrasi', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.prosesregistrasi', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.view', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.ubah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.prosesubah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.cari', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.reportmutasipoin', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membership.reportmutasikoin', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membershipconfig.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'membershipconfig.updatenilai', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'pos.gantimember', ':tipe' => 0, ':deskripsi' => 'POS: Input/ubah memberonline'],

			[':nama' => 'configMembership', ':tipe' => 1, ':deskripsi' => 'Konfigurasi Membership Online'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'configMembership', ':child' => 'membership.index'],
			[':parent' => 'configMembership', ':child' => 'membership.registrasi'],
			[':parent' => 'configMembership', ':child' => 'membership.prosesregistrasi'],
			[':parent' => 'configMembership', ':child' => 'membership.view'],
			[':parent' => 'configMembership', ':child' => 'membership.ubah'],
			[':parent' => 'configMembership', ':child' => 'membership.prosesubah'],
			[':parent' => 'configMembership', ':child' => 'membership.cari'],
			[':parent' => 'configMembership', ':child' => 'membershipconfig.index'],
			[':parent' => 'configMembership', ':child' => 'membershipconfig.updatenilai'],

			[':parent' => 'laporanSemua', ':child' => 'membership.reportmutasipoin'],
			[':parent' => 'laporanSemua', ':child' => 'membership.reportmutasikoin'],

			[':parent' => 'transaksiPos', ':child' => 'pos.gantimember'],

		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m220823_010403_authitem_membershiponline does not support migration down.\n";
		return false;
	}
}
