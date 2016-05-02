<?php

class m160427_084748_barang_rak_allow_null extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m160427_084748_barang_rak_allow_null does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->dropForeignKey('fk_barang_rak', 'barang');
        $this->alterColumn('barang', 'rak_id', 'INT(10) UNSIGNED NULL');
        $this->addForeignKey('fk_barang_rak', 'barang', 'rak_id', 'barang_rak', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        
    }

}
