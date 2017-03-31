<?php

class m170306_022322_set_penerimaan_pengeluaran_default_jenistr_null extends CDbMigration
{

    public function safeUp()
    {
        $this->dropForeignKey('fk_penerimaan_jenis', 'penerimaan');
        $this->dropForeignKey('fk_pengeluaran_jenistrx', 'pengeluaran');

        $this->alterColumn('penerimaan', 'jenis_transaksi_id', 'INT(10) UNSIGNED NOT NULL');
        $this->alterColumn('pengeluaran', 'jenis_transaksi_id', 'INT(10) UNSIGNED NOT NULL');

        $this->addForeignKey('fk_penerimaan_jenis', 'penerimaan', 'jenis_transaksi_id', 'jenis_transaksi', 'id');
        $this->addForeignKey('fk_pengeluaran_jenistrx', 'pengeluaran', 'jenis_transaksi_id', 'jenis_transaksi', 'id');
    }

    public function safeDown()
    {
        echo "m170306_022322_set_penerimaan_pengeluaran_default_jenistr_null does not support migration down.\n";
        return false;
    }

}
