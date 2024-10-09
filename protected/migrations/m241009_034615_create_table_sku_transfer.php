<?php

class m241009_034615_create_table_sku_transfer extends CDbMigration
{
	public function safeUp()
	{

		$tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

		$this->createTable(
			'sku_transfer',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`tanggal` datetime NOT NULL,
				`nomor` varchar(45) DEFAULT NULL,
				`referensi` varchar(45) DEFAULT NULL,
				`tanggal_referensi` date DEFAULT NULL,
				`sku_id` int(10) unsigned DEFAULT NULL,
				`keterangan` varchar(500) DEFAULT NULL,
				`status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0=draft; 1=transfer',
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				UNIQUE KEY `nomor` (`nomor`),
				KEY `fk_sku_transfer_updatedby_idx` (`updated_by`),
				CONSTRAINT `fk_sku_transfer_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);

		$this->createTable(
			'sku_transfer_detail',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`sku_transfer_id` int(10) unsigned NOT NULL,
				`from_barang_id` int(10) unsigned NOT NULL,
				`from_satuan_id` int(10) unsigned NOT NULL,
				`from_qty` int(11) NOT NULL,
				`from_barcode` varchar(30) NOT NULL,
				`from_nama_barang` varchar(45) NOT NULL,
				`from_nama_satuan` varchar(45) NOT NULL,
				`to_barang_id` int(10) unsigned NOT NULL,
				`to_satuan_id` int(10) unsigned NOT NULL,
				`to_qty` int(11) NOT NULL,
				`to_barcode` varchar(30) NOT NULL,
				`to_nama_barang` varchar(45) NOT NULL,
				`to_nama_satuan` varchar(45) NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_sku_transfer_detail_updatedby_idx` (`updated_by`),
				KEY `fk_sku_transfer_detail_from_barang_idx` (`from_barang_id`),
				KEY `fk_sku_transfer_detail_to_barang_idx` (`to_barang_id`),
				KEY `fk_sku_transfer_detail_header_idx` (`sku_transfer_id`),
				KEY `fk_sku_transfer_detail_from_satuan_idx` (`from_satuan_id`),
				KEY `fk_sku_transfer_detail_to_satuan_idx` (`to_satuan_id`),
				CONSTRAINT `fk_sku_transfer_detail_from_barang` FOREIGN KEY (`from_barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_transfer_detail_from_satuan` FOREIGN KEY (`from_satuan_id`) REFERENCES `barang_satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_transfer_detail_header` FOREIGN KEY (`sku_transfer_id`) REFERENCES `sku_transfer` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_transfer_detail_to_barang` FOREIGN KEY (`to_barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_transfer_detail_to_satuan` FOREIGN KEY (`to_satuan_id`) REFERENCES `barang_satuan` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_sku_transfer_detail_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);
	}

	public function safeDown()
	{
		echo "m241009_034615_create_table_sku_transfer does not support migration down.\n";
		return false;
	}
}