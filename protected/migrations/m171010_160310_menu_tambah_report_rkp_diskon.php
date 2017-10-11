<?php

class m171010_160310_menu_tambah_report_rkp_diskon extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id' => 48,
            'root_id' => 1,
            'nama' => 'Diskon Rekap per Barang',
            'icon' => '<i class="fa fa-cart-arrow-down fa-fw"></i>',
            'link' => '/report/rekapdiskon',
            'keterangan' => 'Laporan Diskon, Rekap per Barang',
            'level' => 3,
            'urutan' => 4,
            'status' => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ]
                , "id=:id", [':id' => 78]);
    }

    public function safeDown()
    {
        echo "m171010_160310_menu_tambah_report_rkp_diskon does not support migration down.\n";
        return false;
    }

}
