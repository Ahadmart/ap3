<?php

class m180910_034955_create_table_pesanan_penjualan extends CDbMigration
{

    public function safeUp()
    {
        $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        $this->createTable('pesanan_penjualan',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `nomor` varchar(45) DEFAULT NULL,
                `tanggal` datetime NOT NULL,
                `profil_id` int(10) unsigned NOT NULL,
                `penjualan_id` INT UNSIGNED NULL,
                `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 10:pesan; 20:batal; 30:jual;',
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
                PRIMARY KEY (`id`),
                UNIQUE KEY `nomor_UNIQUE` (`nomor`),
                KEY `fk_pesanan_penjualan_updatedby_idx` (`updated_by`),
                KEY `fk_pesanan_penjualan_penjualan_idx` (`penjualan_id`),			
                CONSTRAINT `fk_pesanan_penjualan_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_pesanan_penjualan_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                ",
                ], $tableOption);

        $this->createTable('pesanan_penjualan_detail',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `pesanan_penjualan_id` int(10) unsigned NOT NULL,
                `barang_id` int(10) unsigned NOT NULL,
                `qty` int(10) unsigned NOT NULL DEFAULT '1',
                `harga_jual` decimal(18,2) NOT NULL,
                `diskon` decimal(18,2) DEFAULT NULL,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_pesanan_penjualan_detail_header_idx` (`pesanan_penjualan_id`),
                KEY `fk_pesanan_penjualan_detail_barang_idx` (`barang_id`),
                KEY `fk_pesanan_penjualan_detail_updatedby_idx` (`updated_by`),			
                CONSTRAINT `fk_pesanan_penjualan_detail_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_pesanan_penjualan_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_pesanan_penjualan_detail_header` FOREIGN KEY (`pesanan_penjualan_id`) REFERENCES `pesanan_penjualan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION"
                ], $tableOption);
    }

    public function safeDown()
    {
        echo "m180910_034955_create_table_pesanan_penjualan does not support migration down.\n";
        return false;
    }

}
