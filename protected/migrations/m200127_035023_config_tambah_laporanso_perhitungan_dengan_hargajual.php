<?php

class m200127_035023_config_tambah_laporanso_perhitungan_dengan_hargajual extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
                    'nama'       => 'laporanso.dengan_hargajual',
                    'nilai'      => '0',
                    'deskripsi'  => 'Perhitungan nilai stok dengan harga jual. 0: harga beli; 1: harga jual',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m200127_035023_config_tambah_laporanso_perhitungan_dengan_hargajual does not support migration down.\n";
        return false;
    }

}
