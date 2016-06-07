<?php

class m151204_102756_tambah_penerimaan_kategori extends CDbMigration
{

    public function up()
    {
        $this->insert('penerimaan_kategori', array(
            'nama' => 'POS',
            'deskripsi' => 'Transaksi Via POS',
            'updated_at' => '2000-01-01 00:00:00',
            'updated_by' => 1,
            'created_at' => '2000-01-01 00:00:00'
                )
        );
    }

    public function down()
    {
        echo "m151204_102756_tambah_penerimaan_kategori does not support migration down.\n";
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
