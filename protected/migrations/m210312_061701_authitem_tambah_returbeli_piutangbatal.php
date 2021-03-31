<?php

class m210312_061701_authitem_tambah_returbeli_piutangbatal extends CDbMigration
{
	public function safeUp()
	{
		$sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

		$params = [
			[':nama' => 'returpembelian.piutang', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'returpembelian.batal', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$this->insertMultiple('AuthItemChild', [
			['parent' => 'transaksiReturPembelian', 'child'  => 'returpembelian.piutang'],
			['parent' => 'transaksiReturPembelian', 'child'  => 'returpembelian.batal'],
		]);
	}

	public function safeDown()
	{
		echo "m210312_061701_authitem_tambah_returbeli_piutangbatal does not support migration down.\n";
		return false;
	}
}
