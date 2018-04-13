<?php

class m180202_155741_create_table_po extends CDbMigration
{
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('po', ["
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `nomor` varchar(45) DEFAULT NULL,
            `tanggal` datetime NOT NULL,
            `profil_id` int(10) unsigned NOT NULL,
            `referensi` varchar(45) DEFAULT NULL,
            `tanggal_referensi` date DEFAULT NULL,
            `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 10:po; 20:beli',
            `pembelian_id` int(10) unsigned DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            UNIQUE KEY `nomor_UNIQUE` (`nomor`),
            KEY `fk_po_updatedby_idx` (`updated_by`),
			KEY `fk_po_profil_idx` (`profil_id`),
			KEY `fk_po_pembelian_idx` (`pembelian_id`),
            CONSTRAINT `fk_po_profil` FOREIGN KEY (`profil_id`) REFERENCES `profil` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_po_pembelian` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_po_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ",
        ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('po_detail', ["
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `po_id` int(10) unsigned NOT NULL,
            `barang_id` int(10) unsigned DEFAULT NULL,
            `barcode` varchar(30) NOT NULL,
            `nama` varchar(45) NOT NULL,
            `harga_beli_terakhir` decimal(18,2) NOT NULL,
			`ads` float DEFAULT '0',
			`stok` int(11) NOT NULL DEFAULT '0',
			`est_sisa_hari` float DEFAULT '0',
			`saran_order` int(11) NOT NULL DEFAULT '0',
            `qty_order` int(10) unsigned NOT NULL DEFAULT '1',
            `status` tinyint(4) NOT NULL DEFAULT '0',
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            KEY `fk_po_detail_po_idx` (`po_id`),
            KEY `fk_po_detail_barang_idx` (`barang_id`),
            KEY `fk_po_detail_updatedby_idx` (`updated_by`),
            CONSTRAINT `fk_po_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_po_detail_header` FOREIGN KEY (`po_id`) REFERENCES `po` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_po_detail_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ",
        ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
        $now = date('Y-m-d H:i:s');
    }

    public function safeDown()
    {
        echo "m180202_155741_create_table_po does not support migration down.\n";
        return false;
    }
}
