<?php

class m170406_044920_fitur_diskon_nominal_dapat_diskon_barang extends CDbMigration
{

    public function safeUp()
    {
        $this->addColumn('barang_diskon', 'barang_bonus_diskon_nominal', 'DECIMAL(18,2) NULL AFTER `barang_bonus_id`');
        $this->addColumn('barang_diskon', 'barang_bonus_diskon_persen', 'FLOAT NULL AFTER `barang_bonus_diskon_nominal`');
    }

    public function safeDown()
    {
        echo "m170406_044920_fitur_diskon_nominal_dapat_diskon_barang does not support migration down.\n";
        return false;
    }

}
