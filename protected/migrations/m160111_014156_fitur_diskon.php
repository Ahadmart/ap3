<?php

class m160111_014156_fitur_diskon extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m160111_014156_fitur_diskon does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('barang_diskon', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `barang_id` int(10) unsigned NOT NULL,
            `tipe_diskon_id` tinyint(4) unsigned NOT NULL COMMENT '1: Promo (pengurangan harga per waktu tertentu)\n2: Grosir (beli banyak harga turun)\n3: Banded (beli qty tertentu harga turun)',
            `nominal` decimal(18,2) NOT NULL,
            `persen` float DEFAULT NULL,
            `dari` datetime NOT NULL,
            `sampai` datetime DEFAULT NULL,
            `qty` int(10) unsigned DEFAULT NULL,
            `qty_min` int(10) unsigned DEFAULT NULL,
            `qty_max` int(10) unsigned DEFAULT NULL,
            `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0:tidak aktif; 1:aktif',
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            KEY `fk_barang_diskon_barang_idx` (`barang_id`),
            KEY `fk_barang_diskon_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('penjualan_diskon', array(
            "`id` int(10) NOT NULL AUTO_INCREMENT,
            `penjualan_detail_id` int(10) unsigned NOT NULL,
            `penjualan_id` int(10) unsigned NOT NULL,
            `harga` decimal(18,2) NOT NULL,
            `harga_normal` decimal(18,2) NOT NULL,
            `tipe_diskon_id` tinyint(3) unsigned DEFAULT NULL COMMENT '1: Promo (pengurangan harga per waktu tertentu)\n2: Grosir (beli banyak harga turun)\n3: Banded (beli qty tertentu harga turun)',
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            KEY `fk_penjualan_diskon_penjualandetail_idx` (`penjualan_detail_id`),
            KEY `fk_penjualan_diskon_penjualan_idx` (`penjualan_id`),
            KEY `fk_penjualan_diskon_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        /* Foreign Key Tabel barang_diskon */
        $this->addForeignKey('fk_barang_diskon_barang', 'barang_diskon', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_diskon_updatedby', 'barang_diskon', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penjualan_diskon */
        $this->addForeignKey('fk_penjualan_diskon_penjualan', 'penjualan_diskon', 'penjualan_id', 'penjualan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_diskon_penjualandetail', 'penjualan_diskon', 'penjualan_detail_id', 'penjualan_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_diskon_updatedby', 'penjualan_diskon', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Tambah Field penjualan_detail.diskon */
        $this->addColumn('penjualan_detail', 'diskon', 'decimal(18,2) DEFAULT NULL AFTER `harga_jual`');
    }

    public function safeDown()
    {
        echo "m160111_014156_fitur_diskon does not support migration down.\n";
        return false;
    }

}
