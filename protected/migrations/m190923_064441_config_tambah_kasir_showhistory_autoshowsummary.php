<?php

class m190923_064441_config_tambah_kasir_showhistory_autoshowsummary extends CDbMigration
{

    public function safeUp()
    {
        $now = date('Y-m-d H:i:s');
        $this->insert('config',
                [
                    'nama'       => 'kasir.showhistory',
                    'nilai'      => '0',
                    'deskripsi'  => 'Kasir: Menampilkan riwayat. 0:Tidak tampil; 1:Tampil',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
        $this->insert('config',
                [
                    'nama'       => 'kasir.showautosummary',
                    'nilai'      => '0',
                    'deskripsi'  => 'Kasir: Menampilkan rekap/cetak setelah tutup kasir. 0:Tidak tampil; 1:Tampil',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
    }

    public function safeDown()
    {
        echo "m190923_064441_config_tambah_kasir_showhistory_autoshowsummary does not support migration down.\n";
        return false;
    }

}
