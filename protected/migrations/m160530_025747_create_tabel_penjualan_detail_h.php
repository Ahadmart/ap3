<?php

class m160530_025747_create_tabel_penjualan_detail_h extends CDbMigration
{

    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('penjualan_detail_h', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `barang_id` int(10) unsigned NOT NULL,
            `barang_barcode` varchar(30) CHARACTER SET latin1 NOT NULL,
            `barang_nama` varchar(45) CHARACTER SET latin1 NOT NULL,
            `harga_beli` decimal(18,2) DEFAULT NULL,
            `harga_jual` decimal(18,2) DEFAULT NULL,
            `user_kasir_id` int(10) unsigned NOT NULL,
            `user_kasir_nama` varchar(45) CHARACTER SET latin1 NOT NULL,
            `user_admin_id` int(10) unsigned NOT NULL,
            `user_admin_nama` varchar(45) CHARACTER SET latin1 NOT NULL,
            `penjualan_id` int(10) unsigned NOT NULL,
            `jenis` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:perbarang, 1:pernota',
            `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `barang_idx` (`barang_id`),
            KEY `barcode_idx` (`barang_barcode`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');
    }

    public function safeDown()
    {
        echo "m160530_025747_create_tabel_penjualan_detail_h does not support migration down.\n";
        return false;
    }

}
