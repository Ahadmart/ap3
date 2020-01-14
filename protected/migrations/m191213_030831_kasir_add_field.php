<?php

class m191213_030831_kasir_add_field extends CDbMigration
{

    public function safeUp()
    {
        $this->addColumn('kasir', 'total_infaq', 'DECIMAL(18,2) NULL AFTER `total_penjualan`');
        $this->addColumn('kasir', 'total_diskon_pernota', 'DECIMAL(18,2) NULL AFTER `total_infaq`');
        $this->addColumn('kasir', 'total_tarik_tunai', 'DECIMAL(18,2) NULL AFTER `total_diskon_pernota`');
        $this->addColumn('kasir', 'total_penerimaan', 'DECIMAL(18,2) NULL AFTER `total_tarik_tunai`');
        $this->addColumn('kasir', 'total_uang_dibayar', 'DECIMAL(18,2) NULL AFTER `total_penerimaan`');
    }

    public function safeDown()
    {
        echo "m191213_030831_kasir_add_field does not support migration down.\n";
        return false;
    }

}
