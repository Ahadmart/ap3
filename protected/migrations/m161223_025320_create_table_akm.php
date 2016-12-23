<?php

class m161223_025320_create_table_akm extends CDbMigration
{

    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('akm', ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `nomor` varchar(45) DEFAULT NULL,
                `tanggal` datetime NOT NULL,
                `profil_id` int(10) unsigned NOT NULL,
                `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 1:simpan;',
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL COMMENT 'ipv4',
                `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
                PRIMARY KEY (`id`),
                UNIQUE KEY `nomor_UNIQUE` (`nomor`)"
                ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('akm_detail', ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `akm_id` int(10) unsigned NOT NULL,
                `barang_id` int(10) unsigned NOT NULL,
                `qty` int(10) unsigned NOT NULL DEFAULT '1',
                `harga_jual` decimal(18,2) NOT NULL,
                `diskon` decimal(18,2) DEFAULT NULL,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `fk_akm_detail_header_idx` (`akm_id`),
                KEY `fk_akm_detail_barang_idx` (`barang_id`),
                CONSTRAINT `fk_akm_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                CONSTRAINT `fk_akm_detail_header` FOREIGN KEY (`akm_id`) REFERENCES `akm` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION"
                ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
    }

    public function safeDown()
    {
        echo "m161223_025320_create_table_akm does not support migration down.\n";
        return false;
    }

}
