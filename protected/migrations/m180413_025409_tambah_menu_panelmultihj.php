<?php

class m180413_025409_tambah_menu_panelmultihj extends CDbMigration
{
	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id'  => 9,
            'root_id'    => 1,
            'nama'       => 'Panel Multi (satuan) Harga Jual',
            'icon'       => '<i class="fa fa-clone fa-fw"></i>',
            'link'       => 'tools/panelmultihj/index',
            'keterangan' => 'Informasi & Editor Multi Harga Jual',
            'level'      => 2,
            'urutan'     => 6,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 81]);
	}

	public function safeDown()
	{
		echo "m180413_025409_tambah_menu_panelmultihj does not support migration down.\n";
		return false;
	}
	
}