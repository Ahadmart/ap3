<?php

class m180130_030302_create_config_pembelian_view_showstok extends CDbMigration
{
    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config', [
            'nama'       => 'pembelian.view.showstok',
            'nilai'      => '0',
            'deskripsi'  => 'Pembelian.View: Tampilkan kolom stok saat ini. 0:Tidak tampil; 1:Tampil',
            'updated_by' => 1,
            'created_at' => $now,
        ]);
    }

    public function safeDown()
    {
        echo "m180130_030302_create_config_pembelian_view_showstok does not support migration down.\n";
        return false;
    }

}
