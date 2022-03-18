<?php

class m220309_055302_authitem_tambah_transaksipos_mobile extends CDbMigration
{
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{$sql    = "INSERT IGNORE INTO `AuthItem` (name, type, description) VALUES (:nama, :tipe, :deskripsi)";
        $params = [
            [':nama' => 'posm.adminlogin', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.adminlogout', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.caribarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.cekharga', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.ganticustomer', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.hapus', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.index', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.inputakm', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.kembalian', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.out', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.simpan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.suspended', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.tambah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.tambahbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.ubah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.updatehargamanual', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.updateqty', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'penjualan.printstruk', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'penjualan.total', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'transaksiPosM', ':tipe' => 1, ':deskripsi' => 'Transaksi POS untuk layar kecil'],

            [':nama' => 'posm.inputpesanan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesanan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesananbaru', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesananprint', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesanansimpan', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesanantambahbarang', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesananubah', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'posm.pesananupdateqty', ':tipe' => 0, ':deskripsi' => ''],
            [':nama' => 'transaksiPesananPosM', ':tipe' => 1, ':deskripsi' => 'Transaksi Pesanan di POS untuk layar kecil'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }

        $sql    = "INSERT IGNORE INTO `AuthItemChild` (parent, child) VALUES (:parent, :child)";
        $params = [
            [':parent' => 'transaksiPosM', ':child' => 'posm.adminlogin'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.adminlogout'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.caribarang'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.cekharga'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.ganticustomer'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.hapus'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.index'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.inputakm'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.kembalian'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.out'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.simpan'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.suspended'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.tambah'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.tambahbarang'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.ubah'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.updatehargamanual'],
            [':parent' => 'transaksiPosM', ':child' => 'posm.updateqty'],
            [':parent' => 'transaksiPosM', ':child' => 'penjualan.printstruk'],
            [':parent' => 'transaksiPosM', ':child' => 'penjualan.total'],
            [':parent' => 'POS', ':child' => 'transaksiPosM'],

            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.inputpesanan'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesanan'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesananbaru'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesananprint'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesanansimpan'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesanantambahbarang'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesananubah'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'posm.pesananupdateqty'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'salesorder.batal'],
            [':parent' => 'transaksiPesananPosM', ':child' => 'salesorder.total'],
            [':parent' => 'POS', ':child' => 'transaksiPesananPosM'],
        ];
        foreach ($params as $param) {
            $this->execute($sql, $param);
        }
	}

	public function safeDown()
	{
		echo "m220309_055302_authitem_tambah_transaksipos_mobile does not support migration down.\n";
		return false;
	}
	
}