<?php

class m181016_015057_create_table_report_penjualanso extends CDbMigration
{

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';

        $this->createTable('report_penjualan_salesorder',
                ["
              `penjualan_id` int(10) unsigned DEFAULT NULL,
              `penjualan_nomor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `so_id` int(10) unsigned DEFAULT NULL,
              `so_nomor` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
              `tanggal` datetime DEFAULT NULL,
              `profil_id` int(10) unsigned DEFAULT NULL,
              `updated_by` int(10) unsigned DEFAULT NULL,
              `total` decimal(18,2) DEFAULT NULL,
              `total_modal` decimal(18,2) DEFAULT NULL,
              `nama` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
              `margin` decimal(18,2) DEFAULT NULL,
              `user_id` int(10) unsigned DEFAULT NULL"
                ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m181016_015057_create_table_report_penjualanso does not support migration down.\n";
        return false;
    }

}
