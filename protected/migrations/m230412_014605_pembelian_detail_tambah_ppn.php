<?php

class m230412_014605_pembelian_detail_tambah_ppn extends CDbMigration
{
	public function safeUp()
	{
		$this->addColumn('pembelian_detail', 'ppn', "DECIMAL(18,2) NULL DEFAULT 0 COMMENT 'Dalam persen (%)' AFTER `harga_jual_rekomendasi`");
	}

	public function safeDown()
	{
		echo "m230412_014605_pembelian_detail_tambah_ppn does not support migration down.\n";
		return false;
	}
}
