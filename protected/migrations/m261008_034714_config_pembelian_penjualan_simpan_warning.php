<?php

class m261008_034714_config_pembelian_penjualan_simpan_warning extends CDbMigration
{
    public function safeUp()
    {
        $this->insert('config', ['nama' => 'pembelian.warning.harga-berubah', 'nilai' => '1', 'deskripsi' => '0: Tidak ada warning; 1: Warning, tapi user tetap boleh save; 2: Blokir save sampai RRP/harga jual diubah', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s')]);
        $this->insert('config', ['nama' => 'penjualan.warning.margin-minus', 'nilai' => '1', 'deskripsi' => '0: Tidak ada warning; 1: Warning, tapi user tetap boleh save; 2: Blokir penjualan dengan margin minus', 'updated_at' => date('Y-m-d H:i:s'), 'updated_by' => 1, 'created_at' => date('Y-m-d H:i:s')]);
    }

    public function safeDown()
    {
        echo "m261008_034714_config_pembelian_penjualan_simpan_warning does not support migration down.\n";
        return false;
    }
}
