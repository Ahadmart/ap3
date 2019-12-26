<?php

class m191226_030834_tambah_config_pos_show_tariktunai extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
                    'nama'       => 'pos.showtariktunai',
                    'nilai'      => '0',
                    'deskripsi'  => 'POS: Menampilkan input tarik tunai. 0:Tidak tampil; 1:Tampil',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m191226_030834_tambah_config_pos_show_tariktunai does not support migration down.\n";
        return false;
    }

}
