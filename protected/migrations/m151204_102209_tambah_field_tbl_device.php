<?php

class m151204_102209_tambah_field_tbl_device extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m151204_102209_tambah_field_pdtbl_device_penjualan does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('device', 'default_printer_id', 'int(10) unsigned DEFAULT NULL AFTER `address`');
        $this->createIndex('fk_device_defaultprinter_idx', 'device', 'default_printer_id');
        /* Foreign Key Tabel device */
        $this->addForeignKey('fk_device_defaultprinter', 'device', 'default_printer_id', 'device', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m151204_102209_tambah_field_pdtbl_device_penjualan does not support migration down.\n";
        return false;
    }

}
