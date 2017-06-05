<?php

class m170605_022505_config_show extends CDbMigration
{

    public function safeUp()
    {
        $this->alterColumn('config', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");
        $this->addColumn('config', 'show', 'TINYINT NULL DEFAULT 1 AFTER `deskripsi`');
    }

    public function safeDown()
    {
        echo "m170605_022505_config_show does not support migration down.\n";
        return false;
    }

}
