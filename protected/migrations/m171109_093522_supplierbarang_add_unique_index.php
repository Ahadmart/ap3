<?php

class m171109_093522_supplierbarang_add_unique_index extends CDbMigration
{

    public function safeUp()
    {
        $this->alterColumn('supplier_barang', 'created_at', "TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00'");
        $this->createIndex('fk_supplier_barang_unique_idx', 'supplier_barang', ['supplier_id', 'barang_id'], true);
    }

    public function safeDown()
    {
        echo "m171109_093522_supplierbarang_add_unique_index does not support migration down.\n";
        return false;
    }

}
