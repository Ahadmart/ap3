<?php

class m190516_030241_tambah_menu_penjualanperkategori extends CDbMigration
{
	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');

        // Geser urutan semua yang ada di menu laporan penjualan kecuali urutan <= 2
        $sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 1
		WHERE
			parent_id = 48 and urutan > 2
		';
        $this->execute($sql);

        $this->update('menu',
                [
            'parent_id'  => 48,
            'root_id'    => 1,
            'nama'       => 'Penjualan per Kategori',
            'icon'       => '<i class="fa fa-file-text-o fa-fw"></i>',
            'link'       => '/report/penjualanperkategori',
            'keterangan' => 'Laporan Penjualan per Item per Kategori per Tanggal',
            'level'      => 3,
            'urutan'     => 3,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 85]);
	}

	public function safeDown()
	{
		echo "m190516_030241_tambah_menu_penjualanperkategori does not support migration down.\n";
		return false;
	}
	
}