<?php

class m220318_092523_tabel_create_membership_config extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
        $tableOptions = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

        $this->createTable(
            'membership_config',
            [
                "
				`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`nama` varchar(45) NOT NULL,
				`nilai` varchar(255) NOT NULL,
				`deskripsi` varchar(1000) DEFAULT NULL,
				`show` tinyint(4) DEFAULT 1,
				`updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
				`updated_by` int(10) unsigned NOT NULL,
				`created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
				PRIMARY KEY (`id`),
				UNIQUE KEY `nama` (`nama`),
				KEY `fk_membership_config_updatedby_idx` (`updated_by`),
				CONSTRAINT `fk_membership_config_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`)"
            ],
            $tableOptions
        );

        $now = date('Y-m-d H:i:s');

        $sql = "
				SELECT 
					nilai
				FROM
					config
				WHERE
					nama = 'toko.kode'
				";
        $tokoKode = $this->getDbConnection()->createCommand($sql)->queryRow();

        $sql = "
				SELECT 
					nilai
				FROM
					config
				WHERE
					nama = 'toko.nama'
				";
        $tokoNama = $this->getDbConnection()->createCommand($sql)->queryRow();

        $this->insertMultiple('membership_config', [
            [
                'nama'       => 'login.kode',
                'nilai'      => $tokoKode['nilai'],
                'deskripsi'  => 'Kode Toko untuk Login',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ],
            [
                'nama'       => 'login.nama',
                'nilai'      => $tokoNama['nilai'],
                'deskripsi'  => 'Nama Toko untuk login',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ],
            [
                'nama'       => 'login.password',
                'nilai'      => '-',
                'deskripsi'  => 'Password untuk login',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ],
            [
                'nama'       => 'url',
                'nilai'      => '-',
                'deskripsi'  => 'Server Ahad Membership',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ],
            [
                'nama'       => 'bearer_token',
                'nilai'      => '-',
                'deskripsi'  => 'Auto generated token',
                'updated_at' => $now,
                'updated_by' => 1,
                'created_at' => $now
            ],
        ]);
	}

	public function safeDown()
	{
		echo "m220318_092523_tabel_create_membership_config does not support migration down.\n";
		return false;
	}
	
}