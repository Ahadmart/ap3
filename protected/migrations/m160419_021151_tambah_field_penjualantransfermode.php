<?php

class m160419_021151_tambah_field_penjualantransfermode extends CDbMigration
{

    public function safeUp()
    {
        $this->addColumn('penjualan', 'transfer_mode', 'TINYINT NOT NULL DEFAULT 0 AFTER `hutang_piutang_id`');
    }

    public function safeDown()
    {
        
    }

}
