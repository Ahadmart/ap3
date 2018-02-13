<?php

class m180213_094506_config_tambah_po_filterpersupplier extends CDbMigration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config', [
            'nama'       => 'po.filterpersupplier',
            'nilai'      => '0',
            'deskripsi'  => 'Filter Barang per Supplier. 0:Bebas; 1:per Supplier',
            'updated_by' => 1,
            'created_at' => $now,
        ]);
    }

    public function safeDown()
    {
        echo "m180213_094506_config_tambah_po_filterpersupplier does not support migration down.\n";
        return false;
    }
}
