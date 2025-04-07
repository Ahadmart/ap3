<?php

class m240610_055613_menu_tambah_cetaklabelbarcode extends CDbMigration
{
	public function safeUp()
	{
        // Geser urutan semua yang ada di menu tools ++
        $sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 1
		WHERE
			parent_id = 9
		';
        $this->execute($sql);

        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id'  => 9,
            'root_id'    => 1,
            'nama'       => 'Cetak Label Barang',
            'icon'       => '<i class="fa fa-barcode fa-fw"></i>',
            'link'       => '/tools/cetaklabelbarang/index',
            'keterangan' => 'Cetak Label Barcode per Barang',
            'level'      => 2,
            'urutan'     => 1,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 103]);
	}

	public function safeDown()
	{
		echo "m240610_055613_menu_tambah_cetaklabelbarcode does not support migration down.\n";
		return false;
	}
}
