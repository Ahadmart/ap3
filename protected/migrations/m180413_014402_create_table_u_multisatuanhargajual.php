<?php

class m180413_014402_create_table_u_multisatuanhargajual extends CDbMigration
{
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('barang_harga_jual_multi', ["
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`barang_id` int(10) unsigned NOT NULL,
			`satuan_id` int(10) unsigned NOT NULL,
			`qty` int(10) unsigned NOT NULL DEFAULT '0',
			`harga` decimal(18,2) NOT NULL,
			`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`updated_by` int(10) unsigned NOT NULL,
			`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
			PRIMARY KEY (`id`),
			KEY `fk_barang_harga_jual_multi_updatedby_idx` (`updated_by`),
			KEY `fk_barang_harga_jual_multi_barang_idx` (`barang_id`),
			KEY `fk_barang_harga_jual_multi_satuan_idx` (`satuan_id`),
			CONSTRAINT `fk_barang_harga_jual_multi_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_barang_harga_jual_multi_satuan` FOREIGN KEY (`satuan_id`) REFERENCES `barang_satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_barang_harga_jual_multi_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ",
        ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('penjualan_multi_harga', ["
			`id` int(10) NOT NULL AUTO_INCREMENT,
			`penjualan_detail_id` int(10) unsigned NOT NULL,
			`penjualan_id` int(10) unsigned NOT NULL,
			`qty` int(10) unsigned NOT NULL DEFAULT '0',
			`harga` decimal(18,2) NOT NULL,
			`harga_normal` decimal(18,2) NOT NULL,
			`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			`updated_by` int(10) unsigned NOT NULL,
			`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
			PRIMARY KEY (`id`),
			KEY `fk_penjualan_multi_harga_penjualandetail_idx` (`penjualan_detail_id`),
			KEY `fk_penjualan_multi_harga_penjualan_idx` (`penjualan_id`),
			KEY `fk_penjualan_multi_harga_updatedby_idx` (`updated_by`),
			CONSTRAINT `fk_penjualan_multi_harga_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_penjualan_multi_harga_penjualandetail` FOREIGN KEY (`penjualan_detail_id`) REFERENCES `penjualan_detail` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			CONSTRAINT `fk_penjualan_multi_harga_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
            ",
        ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
    }

    public function safeDown()
    {
        echo "m180413_014402_create_table_u_multisatuanhargajual does not support migration down.\n";
        return false;
    }
}
