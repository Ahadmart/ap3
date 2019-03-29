<?php

class m190328_014008_tambah_config_pos_show_diskon_infaq extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
            'nama'       => 'pos.showdiskonpernota',
            'nilai'      => '0',
            'deskripsi'  => 'POS: Menampilkan input diskon per nota. 0:Tidak tampil; 1:Tampil',
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
        ]);
        $this->insert('config',
                [
            'nama'       => 'pos.showinfak',
            'nilai'      => '0',
            'deskripsi'  => 'POS: Menampilkan input infak/sedekah. 0:Tidak tampil; 1:Tampil',
            'updated_at' => $now,
            'updated_by' => 1,
            'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m190328_014008_tambah_config_pos_show_diskon_infaq does not support migration down.\n";
        return false;
    }

}
