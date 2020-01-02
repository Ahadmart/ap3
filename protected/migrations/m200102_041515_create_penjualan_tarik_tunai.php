<?php

class m200102_041515_create_penjualan_tarik_tunai extends CDbMigration
{

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable('penjualan_tarik_tunai',
                ["
              `id` int unsigned NOT NULL AUTO_INCREMENT,
              `penjualan_id` int unsigned NOT NULL,
              `kas_bank_id` int unsigned NOT NULL,
              `jumlah` decimal(18,2) NOT NULL DEFAULT '0.00',
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `updated_by` int unsigned NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
              PRIMARY KEY (`id`),
              KEY `fk_penjualan_tarik_tunai_updatedby_idx` (`updated_by`),
              KEY `fk_penjualan_tarik_tunai_header_idx` (`penjualan_id`),
              KEY `fk_penjualan_tarik_tunai_kb_idx` (`kas_bank_id`),
              CONSTRAINT `fk_penjualan_tarik_tunai_header` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan` (`id`),
              CONSTRAINT `fk_penjualan_tarik_tunai_kb` FOREIGN KEY (`kas_bank_id`) REFERENCES `kas_bank` (`id`),
              CONSTRAINT `fk_penjualan_tarik_tunai_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)"
                ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m200102_041515_create_penjualan_tarik_tunai does not support migration down.\n";
        return false;
    }

}
