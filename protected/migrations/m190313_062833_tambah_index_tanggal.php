<?php

class m190313_062833_tambah_index_tanggal extends CDbMigration
{

    public function safeUp()
    {
        $this->createIndex('key_pembelian_tanggal', 'pembelian', 'tanggal');
        $this->createIndex('key_penjualan_tanggal', 'penjualan', 'tanggal');
        $this->createIndex('key_returpembelian_tanggal', 'retur_pembelian', 'tanggal');
        $this->createIndex('key_returpenjualan_tanggal', 'retur_penjualan', 'tanggal');
        $this->createIndex('key_penerimaan_tanggal', 'penerimaan', 'tanggal');
        $this->createIndex('key_pengeluaran_tanggal', 'pengeluaran', 'tanggal');
    }

    public function safeDown()
    {
        echo "m190313_062833_tambah_index_tanggal does not support migration down.\n";
        return false;
    }

}
