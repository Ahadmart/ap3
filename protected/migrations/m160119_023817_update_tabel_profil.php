<?php

class m160119_023817_update_tabel_profil extends CDbMigration
{
    /*
      public function up()
      {
      }

      public function down()
      {
      echo "m160119_023817_update_tabel_profil does not support migration down.\n";
      return false;
      }
     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $this->addColumn('profil', 'nomor', 'VARCHAR(45) NULL AFTER `tipe_id`');
        $this->addColumn('profil', 'identitas', 'VARCHAR(255) NULL AFTER `nomor`');
        $this->addColumn('profil', 'hp', 'VARCHAR(255) NULL AFTER `telp`');
        $this->addColumn('profil', 'jenis_kelamin', "TINYINT NULL COMMENT '0=laki2; 1=perempuan' AFTER `hp`");
        $this->addColumn('profil', 'tanggal_lahir', 'DATE NULL AFTER `jenis_kelamin`');
        $this->addColumn('profil', 'surel', 'VARCHAR(255) NULL AFTER `tanggal_lahir`');
    }

    public function safeDown()
    {
        
    }

}
