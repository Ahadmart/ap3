<?php

class m160526_014029_tambah_config_pembelian_pembulatankeatashj extends CDbMigration
{

    public function up()
    {
        /* Tambah konfigurasi untuk kelipatan pembulatan ke atas untuk harga jual pada input pembelian */
        $this->insert('config', array('nama' => 'pembelian.pembulatankeatashj', 'nilai' => '50', 'deskripsi' => 'Kelipatan pembulatan ke atas untuk harga jual pada input pembelian', 'updated_at' => date("Y-m-d H:i:s", time()), 'updated_by' => 1, 'created_at' => date("Y-m-d H:i:s", time())));
    }

    public function down()
    {
        echo "m160526_014029_tambah_config_pembelian_pembulatankeatashj does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
