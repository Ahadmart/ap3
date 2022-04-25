<?php

class m220318_092607_tabel_create_penjualan_member_online extends CDbMigration
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
				`koin_dipakai` INT(10) UNSIGNED NOT NULL,
				`poin` INT(10) UNSIGNED NOT NULL,
				`koin` INT(10) UNSIGNED NOT NULL,
				`level` INT(10) UNSIGNED NOT NULL,
				`level_nama` VARCHAR(45) NOT NULL,
				`total_poin` INT(10) UNSIGNED NOT NULL,
				`total_koin` INT(10) UNSIGNED NOT NULL,
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
        echo "m220318_092607_tabel_create_penjualan_member_online does not support migration down.\n";
        return false;
    }
}
