<?php

class m210305_030341_authitem_tambah_po_editrestockmin extends CDbMigration
{
	public function safeUp()
	{
		$sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

		$params = [
			[':nama' => 'po.editrestockmin', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$this->insertMultiple('AuthItemChild', [
			['parent' => 'transaksiPO', 'child'  => 'po.editrestockmin'],
		]);
	}

	public function safeDown()
	{
		echo "m210305_030341_authitem_tambah_po_editrestockmin does not support migration down.\n";
		return false;
	}
}
