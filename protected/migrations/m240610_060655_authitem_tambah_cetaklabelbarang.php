<?php

class m240610_060655_authitem_tambah_cetaklabelbarang extends CDbMigration
{
	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'tools/cetaklabelbarang.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.tambahpembelian', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.hapus', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.formpilihprinter', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.cetaklabel', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.tambahbarang', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'tools/cetaklabelbarang.updateqty', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'cetakLabelBarang', ':tipe' => 1, ':deskripsi' => 'Mengoperasikan alat pencetak label (harga) barang'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.index'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.tambahpembelian'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.hapus'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.formpilihprinter'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.cetaklabel'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.tambahbarang'],
			[':parent' => 'cetakLabelBarang', ':child' => 'tools/cetaklabelbarang.updateqty'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m240610_060655_authitem_tambah_cetaklabelbarang does not support migration down.\n";
		return false;
	}
}
