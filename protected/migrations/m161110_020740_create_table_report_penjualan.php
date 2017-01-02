<?php

class m161110_020740_create_table_report_penjualan extends CDbMigration
{

    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('report_penjualan', ["
            `penjualan_id` int(10) unsigned DEFAULT NULL,
            `nomor` varchar(45) DEFAULT NULL,
            `tanggal` datetime DEFAULT NULL,
            `profil_id` int(10) unsigned DEFAULT NULL,
            `updated_by` int(10) unsigned DEFAULT NULL,
            `total` decimal(18,2) DEFAULT NULL,
            `total_modal` decimal(18,2) DEFAULT NULL,
            `nama` varchar(100) DEFAULT NULL,
            `margin` decimal(18,2) DEFAULT NULL,
            `user_id` int(10) unsigned DEFAULT NULL"
                ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
    }

    public function safeDown()
    {
        
    }

}
