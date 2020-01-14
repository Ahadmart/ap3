<?php

class m200113_021248_menu_tambah_laporan_stockopname extends CDbMigration
{

	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');
        $this->update('menu',
                [
                    'parent_id'  => 50,
                    'root_id'    => 1,
                    'nama'       => 'Stock Opname',
                    'icon'       => '<i class="fa fa-check-square-o fa-fw"></i>',
                    'link'       => '/report/stockopname',
                    'keterangan' => 'Laporan Stock Opname',
                    'level'      => 3,
                    'urutan'     => 5,
                    'status'     => 1,
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
                ], 'id=:id', [':id' => 86]);
	}

	public function safeDown()
	{
		echo "m200113_021248_menu_tambah_laporan_stockopname does not support migration down.\n";
		return false;
	}
	
}