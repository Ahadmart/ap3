<?php

class m170118_093121_tambah_ref_ke_returjual extends CDbMigration
{

    public function safeUp()
    {
        /* Penyesuaian agar compatible dengan mysql 5.7 */
        $this->alterColumn('retur_penjualan', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");

        $this->addColumn('retur_penjualan', 'referensi', 'VARCHAR(45) NULL AFTER `profil_id`');
        $this->addColumn('retur_penjualan', 'tanggal_referensi', 'DATE NULL AFTER `referensi`');
    }

    public function safeDown()
    {
        echo "m170118_093121_tambah_ref_ke_returjual does not support migration down.\n";
        return false;
    }

}
