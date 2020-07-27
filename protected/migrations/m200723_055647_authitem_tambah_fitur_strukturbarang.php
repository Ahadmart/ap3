<?php

class m200723_055647_authitem_tambah_fitur_strukturbarang extends CDbMigration
{
	public function safeUp()
	{
		$sql = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";

		$params = [
			[':nama' => 'strukturbarang.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.tambahlv1', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.updateurutan', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.telahdipilihlv1', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.tambahlv2', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.telahdipilihlv2', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.tambahlv3', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.updatenama', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'strukturbarang.rendergrid', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'barang.renderstrukturgrid', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'barang.updatestruktur', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'pembelian.renderstrukturgrid', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'tools/editbarang.formgantistruktur', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/editbarang.gantistruktur', ':tipe' => 0, ':deskripsi' => ''],
            
            [':nama' => 'report.penjualanperstruktur', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.ambilstrukturlv2', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.ambilstrukturlv3', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'report.printpenjualanstruktur', ':tipe' => 0, ':deskripsi' => ''],
            
			[':nama' => 'configStrukturBarang', ':tipe' => 1, ':deskripsi' => 'Konfigurasi Struktur Barang'],
            [':nama' => 'reportPenjualanStruktur', ':tipe' => 1, ':deskripsi' => 'Report Penjualan per Struktur'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$this->insertMultiple('AuthItemChild', [
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.index'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.tambahlv1'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.updateurutan'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.telahdipilihlv1'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.tambahlv2'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.telahdipilihlv2'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.tambahlv3'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.updatenama'],
			['parent' => 'configStrukturBarang', 'child'  => 'strukturbarang.rendergrid'],
			['parent' => 'configBarang', 'child'  => 'barang.renderstrukturgrid'],
			['parent' => 'configBarang', 'child'  => 'barang.updatestruktur'],
			['parent' => 'transaksiPembelian', 'child'  => 'pembelian.renderstrukturgrid'],
			['parent' => 'toolsSemua', 'child'  => 'tools/editbarang.formgantistruktur'],
			['parent' => 'toolsSemua', 'child'  => 'tools/editbarang.gantistruktur'],
			['parent' => 'SUPERVISOR', 'child'  => 'configStrukturBarang'],
            ['parent' => 'reportPenjualanStruktur', 'child' => 'report.penjualanperstruktur'],
            ['parent' => 'reportPenjualanStruktur', 'child' => 'report.ambilstrukturlv2'],
            ['parent' => 'reportPenjualanStruktur', 'child' => 'report.ambilstrukturlv3'],
            ['parent' => 'reportPenjualanStruktur', 'child' => 'report.printpenjualanstruktur'],
            ['parent' => 'laporanSemua', 'child' => 'report.penjualanperstruktur'],
            ['parent' => 'laporanSemua', 'child' => 'report.ambilstrukturlv2'],
            ['parent' => 'laporanSemua', 'child' => 'report.ambilstrukturlv3'],
            ['parent' => 'laporanSemua', 'child' => 'report.printpenjualanstruktur'],
		]);
	}

	public function safeDown()
	{
		echo "m200723_055647_authitem_tambah_fitur_strukturbarang does not support migration down.\n";
		return false;
	}
}
