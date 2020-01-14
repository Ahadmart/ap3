<?php

class m191213_023414_create_penerimaan_pengeluaran_kas_bank extends CDbMigration
{

    public function safeUp()
    {

        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable('penerimaan_kas_bank',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `penerimaan_id` int(10) unsigned NOT NULL,
                `kas_bank_id` int(10) unsigned NOT NULL,
                `keterangan` varchar(5000) NULL,
                `jumlah` decimal(18,2) NOT NULL DEFAULT '0.00',
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
                PRIMARY KEY (`id`),
                KEY `fk_penerimaan_kas_bank_updatedby_idx` (`updated_by`),
                KEY `fk_penerimaan_kas_bank_header_idx` (`penerimaan_id`),
                KEY `fk_penerimaan_kas_bank_kb_idx` (`kas_bank_id`),
                CONSTRAINT `fk_penerimaan_kas_bank_header` FOREIGN KEY (`penerimaan_id`) REFERENCES `penerimaan` (`id`),
                CONSTRAINT `fk_penerimaan_kas_bank_kb` FOREIGN KEY (`kas_bank_id`) REFERENCES `kas_bank` (`id`),
                CONSTRAINT `fk_penerimaan_kas_bank_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)"
                ], $tableOptions);

        $this->createTable('pengeluaran_kas_bank',
                ["
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `pengeluaran_id` int(10) unsigned NOT NULL,
                `kas_bank_id` int(10) unsigned NOT NULL,
                `keterangan` varchar(5000) NULL,
                `jumlah` decimal(18,2) NOT NULL DEFAULT '0.00',
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `updated_by` int(10) unsigned NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
                PRIMARY KEY (`id`),
                KEY `fk_pengeluaran_kas_bank_updatedby_idx` (`updated_by`),
                KEY `fk_pengeluaran_kas_bank_header_idx` (`pengeluaran_id`),
                KEY `fk_pengeluaran_kas_bank_kb_idx` (`kas_bank_id`),
                CONSTRAINT `fk_pengeluaran_kas_bank_header` FOREIGN KEY (`pengeluaran_id`) REFERENCES `pengeluaran` (`id`),
                CONSTRAINT `fk_pengeluaran_kas_bank_kb` FOREIGN KEY (`kas_bank_id`) REFERENCES `kas_bank` (`id`),
                CONSTRAINT `fk_pengeluaran_kas_bank_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)"
                ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m191213_023414_create_penerimaan_pengeluaran_kas_bank does not support migration down.\n";
        return false;
    }

}
