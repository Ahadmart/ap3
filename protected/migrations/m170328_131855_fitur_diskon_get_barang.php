<?php

class m170328_131855_fitur_diskon_get_barang extends CDbMigration
{

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('barang_diskon', 'barang_bonus_id', 'INT unsigned NULL AFTER `qty_max`');
        $this->addColumn('barang_diskon', 'barang_bonus_qty', 'INT unsigned NULL AFTER `barang_bonus_id`');
        $this->createIndex('fk_barang_diskon_barang_bonus_idx', 'barang_diskon', 'barang_bonus_id');
        $this->addForeignKey('fk_barang_diskon_barang_bonus', 'barang_diskon', 'barang_bonus_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m170328_131855_fitur_diskon_get_barang does not support migration down.\n";
        return false;
    }

}
