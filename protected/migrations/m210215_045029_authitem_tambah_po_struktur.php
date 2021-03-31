<?php

class m210215_045029_authitem_tambah_po_struktur extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

		$params = [
			[':nama' => 'po.ambilstrukturlv2', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'po.ambilstrukturlv3', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$this->insertMultiple('AuthItemChild', [
			['parent' => 'transaksiPO', 'child'  => 'po.ambilstrukturlv2'],
			['parent' => 'transaksiPO', 'child'  => 'po.ambilstrukturlv3'],
		]);
	}

	public function safeDown()
	{
		echo "m210215_045029_authitem_tambah_po_struktur does not support migration down.\n";
		return false;
	}
}
