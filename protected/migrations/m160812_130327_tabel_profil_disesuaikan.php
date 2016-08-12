<?php

class m160812_130327_tabel_profil_disesuaikan extends CDbMigration
{

    public function up()
    {
        $this->execute("ALTER TABLE profil AUTO_INCREMENT = 101");
    }

    public function down()
    {
        echo "m160812_130327_tabel_profil_disesuaikan does not support migration down.\n";
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
