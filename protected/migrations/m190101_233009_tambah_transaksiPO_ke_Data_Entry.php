<?php

class m190101_233009_tambah_transaksiPO_ke_Data_Entry extends CDbMigration
{

    public function safeUp()
    {
        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'DATA_ENTRY', ':child' => 'transaksiPO'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m190101_233009_tambah_transaksiPO_ke_Data_Entry does not support migration down.\n";
        return false;
    }

}
