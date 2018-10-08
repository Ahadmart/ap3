<?php

class m181008_034342_create_table_salesorder extends CDbMigration
{

    public function safeUp()
    {
        $tableOption = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        $this->createTable('so',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `nomor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
                `tanggal` datetime NOT NULL,
                `profil_id` int(10) unsigned NOT NULL,
                `penjualan_id` int(10) unsigned DEFAULT NULL,
                `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 10:pesan; 20:batal; 30:jual;',
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
                PRIMARY KEY (`id`),
                UNIQUE KEY `nomor_UNIQUE` (`nomor`),
                KEY `fk_so_updatedby_idx` (`updated_by`),
                KEY `fk_so_profil_idx` (`profil_id`),
                KEY `fk_so_penjualan_idx` (`penjualan_id`),
                CONSTRAINT `fk_so_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_so_profil` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_so_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                ",
                ], $tableOption);

        $this->createTable('so_detail',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `so_id` int(10) unsigned NOT NULL,
                `barang_id` int(10) unsigned NOT NULL,
                `qty` int(10) unsigned NOT NULL DEFAULT '1',
                `harga_jual` decimal(18,2) NOT NULL,
                `diskon` decimal(18,2) DEFAULT NULL,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_so_detail_header_idx` (`so_id`),
                KEY `fk_so_detail_barang_idx` (`barang_id`),
                KEY `fk_so_detail_updatedby_idx` (`updated_by`),
                CONSTRAINT `fk_so_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_so_detail_header` FOREIGN KEY (`so_id`) REFERENCES `so` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_so_detail_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION"
                ], $tableOption);
    }

    public function safeDown()
    {
        echo "m181008_034342_create_table_salesorder does not support migration down.\n";
        return false;
    }

}
