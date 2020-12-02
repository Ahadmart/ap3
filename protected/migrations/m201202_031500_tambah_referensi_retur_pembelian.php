<?php

class m201202_031500_tambah_referensi_retur_pembelian extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
		/* Tambah kolom retur_pembelian.referensi dan retur_pembelian.tanggal_referensi */
		$this->addColumn('retur_pembelian', 'referensi', 'VARCHAR(45) NULL AFTER `hutang_piutang_id`');
		$this->addColumn('retur_pembelian', 'tanggal_referensi', 'DATE NULL AFTER `referensi`');
	}

	public function safeDown()
	{
		echo "m201202_031500_tambah_referensi_retur_pembelian does not support migration down.\n";
		return false;
	}
}
