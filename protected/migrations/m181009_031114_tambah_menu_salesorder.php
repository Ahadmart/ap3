<?php

class m181009_031114_tambah_menu_salesorder extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        // Geser urutan semua yang ada di menu transaksi ++
        $sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 2
		WHERE
			parent_id = 5 and urutan > 1
		';
        $this->execute($sql);

        $this->update('menu',
                [
            'parent_id'  => 5,
            'root_id'    => 1,
            'nama'       => 'Sales Order',
            'icon'       => '<i class="fa fa-file-text fa-fw"></i>',
            'link'       => '/salesorder/index',
            'keterangan' => 'Transaksi Pesanan Penjualan',
            'level'      => 2,
            'urutan'     => 2,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 82]);

        $this->update('menu',
                [
            'parent_id'  => 5,
            'root_id'    => 1,
            'nama'       => '-',
            'keterangan' => 'Divider',
            'level'      => 2,
            'urutan'     => 3,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 83]);
    }

    public function safeDown()
    {
        echo "m181009_031114_tambah_menu_salesorder does not support migration down.\n";
        return false;
    }

}
