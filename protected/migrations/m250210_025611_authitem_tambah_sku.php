<?php

class m250210_025611_authitem_tambah_sku extends CDbMigration
{

	public function safeUp()
	{
		$sql    = 'INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)';
		$params = [
			[':nama' => 'sku.view', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.tambah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.ubah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.hapus', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.tambahbaranglist', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.tambahbarang', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.hapusdetail', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.tambahlevelform', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.hapuslevel', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.updaterasio', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.updatesatuan', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.renderstrukturgrid', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'sku.updatestruktur', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skulevel.view', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skulevel.tambah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skulevel.ubah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skulevel.hapus', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skulevel.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.view', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.tambah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.ubah', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.hapus', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.index', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.carisku', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.getdatasku', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.caribarang', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.rendertujuan', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.konversi', ':tipe' => 0, ':deskripsi' => ''],
			[':nama' => 'skutransfer.transfer', ':tipe' => 0, ':deskripsi' => ''],

			[':nama' => 'configSKU', ':tipe' => 1, ':deskripsi' => 'Konfigurasi SKU'],
			[':nama' => 'transaksiTransferStok', ':tipe' => 1, ':deskripsi' => 'Transfer Stok barang di dalam SKU'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}

		$sql    = 'INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)';
		$params = [
			[':parent' => 'configSKU', ':child' => 'sku.view'],
			[':parent' => 'configSKU', ':child' => 'sku.tambah'],
			[':parent' => 'configSKU', ':child' => 'sku.ubah'],
			[':parent' => 'configSKU', ':child' => 'sku.hapus'],
			[':parent' => 'configSKU', ':child' => 'sku.index'],
			[':parent' => 'configSKU', ':child' => 'sku.tambahbaranglist'],
			[':parent' => 'configSKU', ':child' => 'sku.tambahbarang'],
			[':parent' => 'configSKU', ':child' => 'sku.hapusdetail'],
			[':parent' => 'configSKU', ':child' => 'sku.tambahlevelform'],
			[':parent' => 'configSKU', ':child' => 'sku.hapuslevel'],
			[':parent' => 'configSKU', ':child' => 'sku.updaterasio'],
			[':parent' => 'configSKU', ':child' => 'sku.updatesatuan'],
			[':parent' => 'configSKU', ':child' => 'sku.renderstrukturgrid'],
			[':parent' => 'configSKU', ':child' => 'sku.updatestruktur'],
			[':parent' => 'configSKU', ':child' => 'skulevel.view'],
			[':parent' => 'configSKU', ':child' => 'skulevel.tambah'],
			[':parent' => 'configSKU', ':child' => 'skulevel.ubah'],
			[':parent' => 'configSKU', ':child' => 'skulevel.hapus'],
			[':parent' => 'configSKU', ':child' => 'skulevel.index'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.view'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.tambah'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.ubah'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.hapus'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.index'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.carisku'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.getdatasku'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.caribarang'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.rendertujuan'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.konversi'],
			[':parent' => 'transaksiTransferStok', ':child' => 'skutransfer.transfer'],


			[':parent' => 'DATA_ENTRY', ':child' => 'configSKU'],
			[':parent' => 'DATA_ENTRY', ':child' => 'transaksiTransferStok'],
			[':parent' => 'SUPERVISOR', ':child' => 'transaksiTransferStok'],
			[':parent' => 'SUPERVISOR', ':child' => 'configSKU'],
		];
		foreach ($params as $param) {
			$this->execute($sql, $param);
		}
	}

	public function safeDown()
	{
		echo "m250210_025611_authitem_tambah_sku does not support migration down.\n";
		return false;
	}
}
