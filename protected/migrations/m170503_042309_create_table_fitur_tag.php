<?php

class m170503_042309_create_table_fitur_tag extends CDbMigration
{

    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('tag', array(
            " `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `nama` varchar(45) NOT NULL,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `updated_by` int(10) unsigned NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
              PRIMARY KEY (`id`),
              UNIQUE KEY `nama` (`nama`),
              KEY `fk_tag_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        /* Foreign Key Tabel tag */
        $this->addForeignKey('fk_tag_updatedby', 'tag', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        $this->createTable('tag_barang', array(
            " `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `tag_id` int(10) unsigned NOT NULL,
              `barang_id` int(10) unsigned NOT NULL,
              `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              `updated_by` int(10) unsigned NOT NULL,
              `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
              PRIMARY KEY (`id`),
              KEY `fk_tag_barang_updatedby_idx` (`updated_by`),
              KEY `fk_tag_barang_tag_idx` (`tag_id`),
              KEY `fk_tag_barang_barang_idx` (`barang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        /* Foreign Key Tabel tag_barang */
        $this->addForeignKey('fk_tag_barang_barang', 'tag_barang', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_tag_barang_tag', 'tag_barang', 'tag_id', 'tag', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_tag_barang_updatedby', 'tag_barang', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m170503_042309_create_table_fitur_tag does not support migration down.\n";
        return false;
    }

}
