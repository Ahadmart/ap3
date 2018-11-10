<?php

class m180831_020411_so_tambahfield_inputselisih extends CDbMigration
{

    public function safeUp()
    {
        /* Penyesuaian agar compatible dengan mysql >= 5.7 */
        $this->alterColumn('stock_opname', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");

        $this->addColumn('stock_opname', 'input_selisih', 'TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `status`');
    }

    public function safeDown()
    {
        echo "m180831_020411_so_tambahfield_inputselisih does not support migration down.\n";
        return false;
    }

}
