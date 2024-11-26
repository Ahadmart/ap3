<?php

class m241009_032014_create_table_sku extends CDbMigration
{
	public function safeUp()
	{

		$tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

		$this->createTable(
			'sku',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`nomor` varchar(30) NOT NULL,
				`nama` varchar(45) NOT NULL,
				`struktur_id` int(10) unsigned DEFAULT NULL,
				`kategori_id` int(10) unsigned DEFAULT NULL,
				`status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0=tidak aktif; 1=aktif;',
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
				PRIMARY KEY (`id`),
				UNIQUE KEY `nomor_UNIQUE` (`nomor`),
				KEY `fk_sku_updatedby_idx` (`updated_by`),
				KEY `fk_sku_kategori_idx` (`kategori_id`),
				KEY `nama_sku_idx` (`nama`),
				KEY `fk_sku_struktur_idx` (`struktur_id`),
				CONSTRAINT `fk_sku_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `barang_kategori` (`id`),
				CONSTRAINT `fk_sku_struktur` FOREIGN KEY (`struktur_id`) REFERENCES `barang_struktur` (`id`),
				CONSTRAINT `fk_sku_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);

		$this->createTable(
			'sku_detail',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`sku_id` int(10) unsigned NOT NULL,
				`barang_id` int(10) unsigned NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
				PRIMARY KEY (`id`),
				UNIQUE KEY `uq_sku_detail_skubarang` (`sku_id`,`barang_id`),
				KEY `fk_sku_detail_sku_idx` (`sku_id`),
				KEY `fk_sku_detail_barang_idx` (`barang_id`),
				KEY `fk_sku_detail_updatedby_idx` (`updated_by`),
				CONSTRAINT `fk_sku_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_detail_header` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_detail_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);

		$this->createTable(
			'sku_level',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`sku_id` int(10) unsigned NOT NULL,
				`level` int(10) unsigned NOT NULL DEFAULT 1,
				`satuan_id` int(10) unsigned NOT NULL,
				`rasio_konversi` int(10) unsigned NOT NULL DEFAULT 1,
				`jumlah_per_unit` int(10) unsigned NOT NULL DEFAULT 1,
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT current_timestamp(),
				PRIMARY KEY (`id`),
				KEY `fk_sku_level_header_idx` (`sku_id`),
				KEY `fk_sku_level_satuan_idx` (`satuan_id`),
				KEY `fk_sku_level_user_idx` (`updated_by`),
				CONSTRAINT `fk_sku_level_header` FOREIGN KEY (`sku_id`) REFERENCES `sku` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_level_satuan` FOREIGN KEY (`satuan_id`) REFERENCES `barang_satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_level_user` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);
	}

	public function safeDown()
	{
		echo "m241009_032014_create_table_sku does not support migration down.\n";
		return false;
	}
}
