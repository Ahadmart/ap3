<?php

class m180206_031005_config_tambah_po_caribarang extends CDbMigration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config', [
            'nama'       => 'po.caribarangmode',
            'nilai'      => '1',
            'deskripsi'  => 'Mode Cari Barang. 0:AutoComplete; 1:Table; 2: Double DropDown',
            'updated_by' => 1,
            'created_at' => $now,
        ]);
    }

    public function safeDown()
    {
        echo "m180206_031005_config_tambah_po_caribarang does not support migration down.\n";
        return false;
    }

}
