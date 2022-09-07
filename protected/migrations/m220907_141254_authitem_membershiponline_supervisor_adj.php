<?php

class m220907_141254_authitem_membershiponline_supervisor_adj extends CDbMigration
{
	public function safeUp()
	{
        $this->delete('AuthItemChild', 'parent=:parent AND child=:child', [
            ':parent' => 'configMembership',
            ':child'  => 'membershipconfig.index'
        ]);
        $this->delete('AuthItemChild', 'parent=:parent AND child=:child', [
            ':parent' => 'configMembership',
            ':child'  => 'membershipconfig.updatenilai'
        ]);

        $sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
        $params = [
            [':parent' => 'SUPERVISOR', ':child' => 'configMembership'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
	}

	public function safeDown()
	{
		echo "m220907_141254_authitem_membershiponline_supervisor_adj does not support migration down.\n";
		return false;
	}
	
}