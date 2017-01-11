<?php

class m170111_123339_inputakm_ke_role_kasir extends CDbMigration
{

    public function safeUp()
    {
        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

        $params = [
            [':nama' => 'pos.inputakm', ':tipe' => 0, ':deskripsi' => '']
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiPos', ':child' => 'pos.inputakm']
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        
    }

}
