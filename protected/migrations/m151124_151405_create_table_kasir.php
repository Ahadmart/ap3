<?php

class m151124_151405_create_table_kasir extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m151124_151405_create_table_kasir does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('kasir', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL,
            `device_id` int(10) unsigned NOT NULL,
            `waktu_buka` datetime NOT NULL,
            `waktu_tutup` datetime DEFAULT NULL,
            `saldo_awal` decimal(18,2) NOT NULL,
            `saldo_akhir_seharusnya` decimal(18,2) DEFAULT NULL,
            `saldo_akhir` decimal(18,2) DEFAULT NULL,
            `total_penjualan` decimal(18,2) DEFAULT NULL,
            `total_margin` decimal(18,2) DEFAULT NULL,
            `total_retur` decimal(18,2) DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            KEY `fk_kasir_user_idx` (`user_id`),
            KEY `fk_kasir_device_idx` (`device_id`),
            KEY `fk_kasir_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        /* Foreign Key Tabel kasir */
        $this->addForeignKey('fk_kasir_device', 'kasir', 'device_id', 'device', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_kasir_user', 'kasir', 'user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_kasir_updatedby', 'kasir', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m151124_151405_create_table_kasir does not support migration down.\n";
        return false;
    }

}
