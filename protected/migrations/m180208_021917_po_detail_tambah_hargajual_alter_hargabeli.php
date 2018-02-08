<?php

class m180208_021917_po_detail_tambah_hargajual_alter_hargabeli extends CDbMigration
{
    public function safeUp()
    {
        $this->renameColumn('po_detail', 'harga_beli_terakhir', 'harga_beli');
        $this->addColumn('po_detail', 'harga_jual', 'DECIMAL(18,2) NOT NULL DEFAULT 0 AFTER `harga_beli`');
    }

    public function safeDown()
    {
        echo "m180208_021917_po_detail_tambah_hargajual_alter_hargabeli does not support migration down.\n";
        return false;
    }
}
