<?php

class m230124_092815_create_table_barang_diskon_mol_level extends CDbMigration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';
        $this->createTable(
            'barang_diskon_mol_level',
            [
                "
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`barang_diskon_id` int(11) unsigned NOT NULL,
				`level` int(10) unsigned NOT NULL,
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				KEY `fk_barang_diskon_mol_level_1_idx` (`barang_diskon_id`),
				CONSTRAINT `fk_barang_diskon_mol_level_1` FOREIGN KEY (`barang_diskon_id`) REFERENCES `barang_diskon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION",
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        echo "m230124_092815_create_table_barang_diskon_mol_level does not support migration down.\n";
        return false;
    }
}
