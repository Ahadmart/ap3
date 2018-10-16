<?php

class m181016_011709_menu_tambah_laporan_penjualanviaso extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');

        // Geser urutan semua yang ada di menu laporan penjualan kecuali urutan 1
        $sql = '
		UPDATE menu 
		SET 
			urutan = urutan + 1
		WHERE
			parent_id = 48 and urutan > 1
		';
        $this->execute($sql);

        $this->update('menu',
                [
            'parent_id'  => 48,
            'root_id'    => 1,
            'nama'       => 'Penjualan via Sales Order per Nota',
            'icon'       => '<i class="fa fa-line-chart fa-fw"></i>',
            'link'       => '/report/penjualansalesorder',
            'keterangan' => 'Laporan Penjualan yang dilakukan lewat Sales Order',
            'level'      => 3,
            'urutan'     => 2,
            'status'     => 1,
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
                ], 'id=:id', [':id' => 84]);
    }

    public function safeDown()
    {
        echo "m181016_011709_menu_tambah_laporan_penjualanviaso does not support migration down.\n";
        return false;
    }

}
