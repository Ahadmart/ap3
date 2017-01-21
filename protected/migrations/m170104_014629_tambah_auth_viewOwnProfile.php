<?php

class m170104_014629_tambah_auth_viewOwnProfile extends CDbMigration
{

    public function safeUp()
    {
        /* Insert Authorization Item */
        $sql = "INSERT IGNORE INTO `AuthItem` (name, type, description, bizrule) VALUES (:nama, :tipe, :deskripsi, :bizrule)";
        $params = [
            [':nama' => 'user.view', ':tipe' => 0, ':deskripsi' => '', ':bizrule' => ""],
            [':nama' => 'viewOwnProfile', ':tipe' => 1, ':deskripsi' => 'View Own Profile', ':bizrule' => "return Yii::app()->user->id==Yii::app()->request->getParam('id');"]
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        /* Insert into authenticated role */
        $sql = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'authenticated', ':child' => 'viewOwnProfile'],
            [':parent' => 'viewOwnProfile', ':child' => 'user.view'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
    }

    public function safeDown()
    {
        echo "m170104_014629_tambah_auth_viewOwnProfile does not support migration down.\n";
        return false;
    }

}
