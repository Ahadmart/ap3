<?php

class m180208_092617_tambah_menu_po extends CDbMigration
{
    public function safeUp()
    {
        // Geser urutan semua yang ada di menu transaksi ++
        $sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 1
		WHERE
			parent_id = 5
		';
        $this->execute($sql);

        $now = date('Y-m-d H:i:s');

        $this->update('menu', [
            'parent_id'  => 5,
            'root_id'    => 1,
            'nama'       => 'Purchase Order',
            'icon'       => '<i class="fa fa-file-text fa-fw"></i>',
            'link'       => '/po/index',
            'keterangan' => 'Transaksi Pemesanan Barang',
            'level'      => 2,
            'urutan'     => 1,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 80]);
    }

    public function safeDown()
    {
        echo "m180208_092617_tambah_menu_po does not support migration down.\n";
        return false;
    }
}
