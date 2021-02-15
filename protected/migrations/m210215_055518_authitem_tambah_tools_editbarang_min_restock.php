<?php

class m210215_055518_authitem_tambah_tools_editbarang_min_restock extends CDbMigration
{
	public function safeUp()
	{
		$sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

		$params = [
			[':nama' => 'tools/editbarang.formminimumrestock', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/editbarang.setminimumrestock', ':tipe' => 0, ':deskripsi' => ''],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$this->insertMultiple('AuthItemChild', [
			['parent' => 'toolsSemua', 'child'  => 'tools/editbarang.formminimumrestock'],
			['parent' => 'toolsSemua', 'child'  => 'tools/editbarang.setminimumrestock'],
		]);
	}

	public function safeDown()
	{
		echo "m210215_055518_authitem_tambah_tools_editbarang_min_restock does not support migration down.\n";
		return false;
	}
}
