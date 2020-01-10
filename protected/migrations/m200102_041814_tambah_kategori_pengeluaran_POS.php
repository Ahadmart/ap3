<?php

class m200102_041814_tambah_kategori_pengeluaran_POS extends CDbMigration
{
	public function safeUp()
	{
        $now = date('Y-m-d H:i:s');
        $this->insert('pengeluaran_kategori',
                [
                    'nama'       => 'POS',
                    'deskripsi'  => 'Transaksi Via POS',
                    'updated_at' => $now,
                    'updated_by' => 1,
                    'created_at' => $now
        ]);
	}

	public function safeDown()
	{
		echo "m200102_041814_tambah_kategori_pengeluaran_POS does not support migration down.\n";
		return false;
	}
	
}