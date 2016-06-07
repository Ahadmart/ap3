<?php

class m160328_025255_create_tabel_label_rak_cetak extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m160328_025255_create_tabel_label_rak_cetak does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('label_rak_cetak', array(
            "`barang_id` int(10) unsigned NOT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`barang_id`),
            KEY `fk_label_rak_cetak_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        /* Foreign Key Tabel label_rak_cetak */

        $this->addForeignKey('fk_label_rak_cetak_barang', 'label_rak_cetak', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_label_rak_cetak_updatedby', 'label_rak_cetak', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {

    }

}
