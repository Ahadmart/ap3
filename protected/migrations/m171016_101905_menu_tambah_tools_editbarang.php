<?php

class m171016_101905_menu_tambah_tools_editbarang extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id' => 9,
            'root_id' => 1,
            'nama' => 'Edit Barang',
            'icon' => '<i class="fa fa-edit fa-fw"></i>',
            'link' => 'tools/editbarang/index',
            'keterangan' => 'Edit Barang',
            'level' => 2,
            'urutan' => 5,
            'status' => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ]
                , "id=:id", [':id' => 79]);
    }

    public function safeDown()
    {
        echo "m171016_101905_menu_tambah_tools_editbarang does not support migration down.\n";
        return false;
    }

}
