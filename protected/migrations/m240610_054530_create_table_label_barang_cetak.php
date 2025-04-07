<?php

class m240610_054530_create_table_label_barang_cetak extends CDbMigration
{
	public function safeUp()
	{
		$dbEngine = 'InnoDB';

		$this->createTable('label_barang_cetak', [
			"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`barang_id` int(10) unsigned NOT NULL,
				`qty` int(10) unsigned NOT NULL,
				`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_label_barang_cetak_barang_idx` (`barang_id`),
				KEY `fk_label_barang_cetak_updatedby_idx` (`updated_by`),
				CONSTRAINT `fk_label_barang_cetak_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_label_barang_cetak_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
				 "
		], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
	}

	public function safeDown()
	{
		echo "m240610_054530_create_table_label_barang_cetak does not support migration down.\n";
		return false;
	}
}
