<?php

class m170605_023023_hide_config_saldo_awal extends CDbMigration
{

    public function safeUp()
    {
        $this->update('config', ['show' => 0], "nama='keuangan.saldo_awal'");
    }

    public function safeDown()
    {
        echo "m170605_023023_hide_config_saldo_awal does not support migration down.\n";
        return false;
    }

}
