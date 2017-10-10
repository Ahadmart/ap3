<?php

class m171010_085223_menu_tambah_report_diskon extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id' => 48,
            'root_id' => 1,
            'nama' => 'Diskon Detail per Barang',
            'icon' => '<i class="fa fa-cart-arrow-down fa-fw"></i>',
            'link' => '/report/diskon',
            'keterangan' => 'Laporan Diskon, Detail per Nota per Barang',
            'level' => 3,
            'urutan' => 3,
            'status' => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ]
                , "id=:id", [':id' => 77]);
    }

    public function safeDown()
    {
        echo "m171010_085223_menu_tambah_report_diskon does not support migration down.\n";
        return false;
    }

}
