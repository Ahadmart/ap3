<?php

class m231113_021510_create_table_parameter_analisa_pls extends CDbMigration
{
	public function safeUp()
	{
		$tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

		$this->createTable(
			'po_analisapls_param',
			[
				"
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`po_id` int(10) unsigned NOT NULL,
				`range` int(10) unsigned NOT NULL,
				`order_period` int(10) unsigned NOT NULL,
				`lead_time` int(10) unsigned NOT NULL,
				`ssd` int(10) unsigned NOT NULL,
				`rak_id` int(10) unsigned DEFAULT NULL,
				`struktur_lv1` int(10) unsigned DEFAULT NULL,
				`struktur_lv2` int(10) unsigned DEFAULT NULL,
				`struktur_lv3` int(10) unsigned DEFAULT NULL,
				`status` tinyint(4) NOT NULL DEFAULT 0,
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_po_analisapls_param_po_idx` (`po_id`),
				KEY `fk_po_analisapls_param_rak_idx` (`rak_id`),
				KEY `fk_po_analisapls_param_struk1_idx` (`struktur_lv1`),
				KEY `fk_po_analisapls_param_struk2_idx` (`struktur_lv2`),
				KEY `fk_po_analisapls_param_struk3_idx` (`struktur_lv3`),
				KEY `fk_po_analisapls_param_user_idx` (`updated_by`),
				CONSTRAINT `fk_po_analisapls_param_po` FOREIGN KEY (`po_id`) REFERENCES `po` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_po_analisapls_param_rak` FOREIGN KEY (`rak_id`) REFERENCES `barang_rak` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_po_analisapls_param_struk1` FOREIGN KEY (`struktur_lv1`) REFERENCES `barang_struktur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_po_analisapls_param_struk2` FOREIGN KEY (`struktur_lv2`) REFERENCES `barang_struktur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_po_analisapls_param_struk3` FOREIGN KEY (`struktur_lv3`) REFERENCES `barang_struktur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_po_analisapls_param_user` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
			],
			$tableOptions
		);
	}

	public function safeDown()
	{
		echo "m231113_021510_create_table_parameter_analisa_pls does not support migration down.\n";
		return false;
	}
}
