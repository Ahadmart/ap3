<?php

class m160907_043302_device_tambah_field_untuk_lpr extends CDbMigration
{

    public function safeUp()
    {
        /* Penyesuaian agar compatible dengan mysql 5.7 */
        $this->alterColumn('device', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");

        /* Tambah Field */
        $this->addColumn('device', 'paper_autocut', 'TINYINT NULL AFTER `lf_setelah`');
        $this->addColumn('device', 'cashdrawer_kick', 'TINYINT NULL AFTER `paper_autocut`');
    }

    public function safeDown()
    {
        echo "m160907_043302_device_tambah_field_untuk_lpr does not support migration down.\n";
        return false;
    }

}
