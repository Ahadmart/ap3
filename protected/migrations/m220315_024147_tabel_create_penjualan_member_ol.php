<?php

class m220315_024147_tabel_create_penjualan_member_ol extends CDbMigration
{
	
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable(
            'penjualan_member_online',
            [
                "
				`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
				`nomor_member` VARCHAR(45) NOT NULL,
				`penjualan_id` INT(10) UNSIGNED NOT NULL,
				`poin_cashback_dipakai` INT(10) UNSIGNED NOT NULL,
				`poin_utama` INT(10) UNSIGNED NOT NULL,
				`poin_cashback` INT(10) UNSIGNED NOT NULL,
				`level` INT(10) UNSIGNED NOT NULL,
				`levelNama` VARCHAR(45) NOT NULL,
				`totalPoin` INT(10) UNSIGNED NOT NULL,
				`totalCashback` INT(10) UNSIGNED NOT NULL,
				`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP () ON UPDATE CURRENT_TIMESTAMP (),
				`updated_by` INT(10) UNSIGNED NOT NULL,
				`created_at` TIMESTAMP NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_penjualan_member_online_penjualan_idx` (`penjualan_id`),
				KEY `fk_penjualan_member_online_updatedby_idx` (`updated_by`),
				CONSTRAINT `fk_penjualan_member_online_penjualan` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`),
				CONSTRAINT `fk_penjualan_member_online_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)"
            ],
            $tableOptions
        );
	}

	public function safeDown()
	{
		echo "m220315_024147_tabel_create_penjualan_member_ol does not support migration down.\n";
		return false;
	}

}