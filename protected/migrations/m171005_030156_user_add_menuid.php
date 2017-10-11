<?php

class m171005_030156_user_add_menuid extends CDbMigration
{

    public function safeUp()
    {
        $this->alterColumn('user', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'"); // Agar tidak error di mysql 5.7
        $this->addColumn('user', 'menu_id', 'INT UNSIGNED NOT NULL DEFAULT 1 AFTER `theme_id`');
        $this->addForeignKey('fk_user_menu', 'user', 'menu_id', 'menu', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m171005_030156_user_add_menuid does not support migration down.\n";
        return false;
    }

}
