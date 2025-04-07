<?php

class m240103_013547_authitem_supervisor_tdkbisa_simpan_stockopname extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		$this->delete('AuthItemChild', "parent=:parent AND child=:child", [
			':parent' => 'SUPERVISOR',
			':child'  => 'transaksiSO'
		]);
		$sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
		$params = [
			[':parent' => 'SUPERVISOR', ':child' => 'transaksiSO-simpan'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m240103_013547_authitem_supervisor_tdkbisa_simpan_stockopname does not support migration down.\n";
		return false;
	}
}
