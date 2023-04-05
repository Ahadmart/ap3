<?php

class m230405_034046_tabel_create_pembelian_ppn extends CDbMigration
{
	public function safeUp()
	{
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable(
            'pembelian_ppn',
            [
                "
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`pembelian_id` int(10) unsigned NOT NULL,
				`no_faktur_pajak` varchar(45) DEFAULT NULL,
				`total_ppn_hitung` decimal(18,2) NOT NULL,
				`total_ppn_faktur` decimal(18,2) NOT NULL,
				`status` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT '0:draft; 1:pending; 2:valid;',
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_pembelian_ppn_updatedby_idx` (`updated_by`),
				KEY `fk_pembelian_ppn_pembelian_idx` (`pembelian_id`),
				CONSTRAINT `fk_pembelian_ppn_pembelian` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
				CONSTRAINT `fk_pembelian_ppn_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION"
            ],
            $tableOptions
        );
	}

	public function safeDown()
	{
		echo "m230405_034046_tabel_create_pembelian_ppn does not support migration down.\n";
		return false;
	}
	
}