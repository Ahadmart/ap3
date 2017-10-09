<?php

class m171004_135042_init_tabel_menu extends CDbMigration
{

    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('menu', ["
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `parent_id` int(10) unsigned DEFAULT NULL,
            `root_id` int(10) unsigned DEFAULT NULL,
            `nama` varchar(128) CHARACTER SET utf8 NOT NULL,
            `icon` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
            `link` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
            `keterangan` varchar(1024) CHARACTER SET utf8 DEFAULT NULL,
            `level` tinyint(4) NOT NULL DEFAULT '0',
            `urutan` tinyint(1) NOT NULL DEFAULT '1',
            `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=not publish; 1=publish; 2=reserved',
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            KEY `fk_menu_updatedby_idx` (`updated_by`),
            KEY `fk_menu_parent_idx` (`parent_id`),
            KEY `nama_menu_idx` (`nama`),
            CONSTRAINT `fk_menu_parent` FOREIGN KEY (`parent_id`) REFERENCES `menu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_menu_updatedby` FOREIGN KEY (`updated_by`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION"
                ], 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $now = date('Y-m-d H:i:s');
        $this->insertMultiple('menu', [
            ['id' => 1, 'parent_id' => NULL, 'root_id' => NULL, 'nama' => 'Default', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Main Menu (Semua Item, Icon: Fontawesome)', 'level' => 0, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 2, 'parent_id' => 1, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 3, 'parent_id' => 1, 'root_id' => 1, 'nama' => 'Config', 'icon' => '<i class="fa fa-cogs fa-fw fa-lg"></i>', 'link' => NULL, 'keterangan' => 'Konfigurasi', 'level' => 1, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 4, 'parent_id' => 1, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 5, 'parent_id' => 1, 'root_id' => 1, 'nama' => 'Transaksi', 'icon' => '<i class="fa fa-calculator fa-fw fa-lg"></i>', 'link' => NULL, 'keterangan' => 'Transaksi', 'level' => 1, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 6, 'parent_id' => 1, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 7, 'parent_id' => 1, 'root_id' => 1, 'nama' => 'Laporan', 'icon' => '<i class="fa fa-pie-chart fa-fw fa-lg"></i>', 'link' => NULL, 'keterangan' => 'Laporan', 'level' => 1, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 8, 'parent_id' => 1, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 7, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 9, 'parent_id' => 1, 'root_id' => 1, 'nama' => 'Tools', 'icon' => '<i class="fa fa-wrench fa-fw fa-lg"></i>', 'link' => NULL, 'keterangan' => 'Program Bantu', 'level' => 1, 'urutan' => 8, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 10, 'parent_id' => 1, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 9, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 11, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Barang', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Barang Menu', 'level' => 2, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 12, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Keuangan', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Keuangan Menu', 'level' => 2, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 13, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Akses', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Authentication & Authorization', 'level' => 2, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 14, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Aplikasi', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Setting Aplikasi', 'level' => 2, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 15, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Profil', 'icon' => '<i class="fa fa-address-book fa-fw"></i>', 'link' => '/profil/index', 'keterangan' => 'Setting Profil Karyawan, Supplier, & Customer', 'level' => 2, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 16, 'parent_id' => 3, 'root_id' => 1, 'nama' => 'Devices', 'icon' => '<i class="fa fa-microchip fa-fw"></i>', 'link' => '/device/index', 'keterangan' => 'Setting Komputer & printer', 'level' => 2, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 17, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Barang', 'icon' => '<i class="fa fa-barcode fa-fw"></i>', 'link' => '/barang/index', 'keterangan' => 'Item Barang', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 18, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Satuan', 'icon' => '<i class="fa fa-tag fa-fw"></i>', 'link' => '/satuanbarang/index', 'keterangan' => 'Satuan Barang', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 19, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Kategori', 'icon' => '<i class="fa fa-tags fa-fw"></i>', 'link' => '/kategoribarang/index', 'keterangan' => 'Kategori Barang', 'level' => 3, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 20, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Rak', 'icon' => '<i class="fa fa-server fa-fw"></i>', 'link' => '/rakbarang/index', 'keterangan' => 'Rak Barang', 'level' => 3, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 21, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Tag', 'icon' => '<i class="fa fa-tags fa-fw"></i>', 'link' => '/tag/index', 'keterangan' => 'Tag Barang', 'level' => 3, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 22, 'parent_id' => 11, 'root_id' => 1, 'nama' => 'Diskon', 'icon' => '<i class="fa fa-cart-arrow-down fa-fw"></i>', 'link' => '/diskonbarang/index', 'keterangan' => 'Diskon Barang', 'level' => 3, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 23, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Kas/Bank', 'icon' => '<i class="fa fa-credit-card fa-fw"></i>', 'link' => '/kasbank/index', 'keterangan' => 'Akun Kas / Bank', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 24, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Jenis Transaksi', 'icon' => '<i class="fa fa-credit-card fa-fw"></i>', 'link' => '/jenistransaksi/index', 'keterangan' => 'Tunai / Transfer', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 25, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Kategori Pengeluaran', 'icon' => '<i class="fa fa-credit-card fa-fw"></i>', 'link' => '/kategoripengeluaran/index', 'keterangan' => 'Kategori Nota Pengeluaran', 'level' => 3, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 26, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Kategori Penerimaan', 'icon' => '<i class="fa fa-credit-card-alt fa-fw"></i>', 'link' => '/kategoripenerimaan/index', 'keterangan' => 'Kategori Nota Penerimaan', 'level' => 3, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 27, 'parent_id' => 12, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 3, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 28, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Item Pengeluaran', 'icon' => '<i class="fa fa-book fa-fw"></i>', 'link' => '/itempengeluaran/index', 'keterangan' => 'COA Dummy', 'level' => 3, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 29, 'parent_id' => 12, 'root_id' => 1, 'nama' => 'Item Penerimaan', 'icon' => '<i class="fa fa-book fa-fw"></i>', 'link' => '/itempenerimaan/index', 'keterangan' => 'COA Dummy', 'level' => 3, 'urutan' => 7, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 30, 'parent_id' => 13, 'root_id' => 1, 'nama' => 'User', 'icon' => '<i class="fa fa-user fa-fw"></i>', 'link' => '/user/index', 'keterangan' => 'Pengguna Aplikasi', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 31, 'parent_id' => 13, 'root_id' => 1, 'nama' => 'Item Otorisasi', 'icon' => '<i class="fa fa-shield fa-fw"></i>', 'link' => '/auth/item/index', 'keterangan' => 'Daftar Item yang bisa digunakan untuk otorisasi', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 32, 'parent_id' => 14, 'root_id' => 1, 'nama' => 'Config', 'icon' => '<i class="fa fa-sliders fa-fw"></i>', 'link' => '/config/index', 'keterangan' => 'Konfigurasi Aplikasi', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 33, 'parent_id' => 14, 'root_id' => 1, 'nama' => 'Menu', 'icon' => '<i class="fa fa-bars fa-fw"></i>', 'link' => '/menu/index', 'keterangan' => 'Konfigurasi Menu', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 34, 'parent_id' => 14, 'root_id' => 1, 'nama' => 'Membership', 'icon' => '<i class="fa fa-vcard fa-fw"></i>', 'link' => '/member/index', 'keterangan' => 'Konfigurasi Keanggotaan', 'level' => 3, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 35, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Pembelian', 'icon' => '<i class="fa fa-truck fa-fw"></i>', 'link' => '/pembelian/index', 'keterangan' => 'Transaksi Pembelian', 'level' => 2, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 36, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Retur Pembelian', 'icon' => '<i class="fa fa-truck fa-flip-horizontal fa-fw"></i>', 'link' => '/returpembelian/index', 'keterangan' => 'Transaksi Retur Pembelian', 'level' => 2, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 37, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Penjualan', 'icon' => '<i class="fa fa-shopping-cart fa-fw"></i>', 'link' => '/penjualan/index', 'keterangan' => 'Transaksi Penjualan', 'level' => 2, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 38, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Retur Penjualan', 'icon' => '<i class="fa fa-shopping-cart fa-flip-horizontal fa-fw"></i>', 'link' => '/returpenjualan/index', 'keterangan' => 'Transaksi Retur Penjualan', 'level' => 2, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 39, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Stock Opname', 'icon' => '<i class="fa fa-check-square-o fa-fw"></i>', 'link' => '/stockopname/index', 'keterangan' => 'Transaksi Stock Opname', 'level' => 2, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 40, 'parent_id' => 5, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 2, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 41, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'POS', 'icon' => '<i class="fa fa-shopping-cart fa-fw"></i>', 'link' => '/pos/index', 'keterangan' => 'Point Of Sales', 'level' => 2, 'urutan' => 7, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 42, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Kasir Buka/Tutup', 'icon' => '<i class="fa fa-key fa-fw"></i>', 'link' => '/kasir/index', 'keterangan' => 'Buka Tutup Kasir', 'level' => 2, 'urutan' => 8, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 43, 'parent_id' => 5, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 2, 'urutan' => 9, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 44, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Pengeluaran', 'icon' => '<i class="fa fa-credit-card fa-fw"></i>', 'link' => '/pengeluaran/index', 'keterangan' => 'Transaksi Pengeluaran (Uang)', 'level' => 2, 'urutan' => 10, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 45, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Penerimaan', 'icon' => '<i class="fa fa-credit-card-alt fa-fw"></i>', 'link' => '/penerimaan/index', 'keterangan' => 'Transaksi Penerimaan (uang)', 'level' => 2, 'urutan' => 11, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 46, 'parent_id' => 5, 'root_id' => 1, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 2, 'urutan' => 12, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 47, 'parent_id' => 5, 'root_id' => 1, 'nama' => 'Data Harian', 'icon' => '<i class="fa fa-list fa-fw"></i>', 'link' => '/laporanharian/index', 'keterangan' => 'Saldo Akhir Harian', 'level' => 2, 'urutan' => 13, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 48, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Penjualan', 'icon' => NULL, 'link' => NULL, 'keterangan' => NULL, 'level' => 2, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 49, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Pembelian', 'icon' => NULL, 'link' => NULL, 'keterangan' => NULL, 'level' => 2, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 50, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Stok', 'icon' => NULL, 'link' => NULL, 'keterangan' => NULL, 'level' => 2, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 51, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Keuangan', 'icon' => NULL, 'link' => NULL, 'keterangan' => NULL, 'level' => 2, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 52, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Transaksi Harian', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Laporan Cash Flow Harian', 'level' => 2, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 53, 'parent_id' => 48, 'root_id' => 1, 'nama' => 'Penjualan Detail per Nota', 'icon' => '<i class="fa fa-line-chart fa-fw"></i>', 'link' => '/report/penjualan', 'keterangan' => 'Laporan Penjualan per Nota', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 54, 'parent_id' => 49, 'root_id' => 1, 'nama' => 'Retur Pembelian per Nota', 'icon' => '<i class="fa fa-reply fa-fw"></i>', 'link' => '/report/returpembelian', 'keterangan' => 'Laporan Retur Pembelian per Nota', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 55, 'parent_id' => 50, 'root_id' => 1, 'nama' => 'Total Stok', 'icon' => '<i class="fa fa-database fa-fw"></i>', 'link' => '/report/totalstok', 'keterangan' => 'Laporan Total Stok (yang tidak minus)', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 56, 'parent_id' => 48, 'root_id' => 1, 'nama' => 'Top Rank / Slow Moving', 'icon' => '<i class="fa fa-sort-amount-desc fa-fw"></i>', 'link' => '/report/toprank', 'keterangan' => 'Laporan Penjualan Per Barang Sortable', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 57, 'parent_id' => 50, 'root_id' => 1, 'nama' => 'Umur Barang (Aging of Inventory)', 'icon' => '<i class="fa fa-hourglass-half fa-fw"></i>', 'link' => '/report/umurbarang', 'keterangan' => 'Laporan Barang Tidak Laku', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 58, 'parent_id' => 50, 'root_id' => 1, 'nama' => 'Kartu Stok', 'icon' => '<i class="fa fa-list fa-fw"></i>', 'link' => '/report/kartustok', 'keterangan' => 'Laporan Mutasi Barang', 'level' => 3, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 59, 'parent_id' => 50, 'root_id' => 1, 'nama' => 'Daftar Barang', 'icon' => '<i class="fa fa-list fa-fw"></i>', 'link' => '/report/daftarbarang', 'keterangan' => 'Cetak Barang', 'level' => 3, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 60, 'parent_id' => 51, 'root_id' => 1, 'nama' => 'Hutang Piutang per Profil', 'icon' => '<i class="fa fa-balance-scale fa-fw"></i>', 'link' => '/report/hutangpiutang', 'keterangan' => 'Laporan Hutang Piutang Detail', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 61, 'parent_id' => 51, 'root_id' => 1, 'nama' => 'Rekap Hutang Piutang', 'icon' => '<i class="fa fa-balance-scale fa-fw"></i>', 'link' => '/report/rekaphutangpiutang', 'keterangan' => 'Laporan Rekap Hutang Piutang', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 62, 'parent_id' => 51, 'root_id' => 1, 'nama' => 'Pengeluaran/Penerimaan', 'icon' => '<i class="fa fa-exchange fa-fw"></i>', 'link' => '/report/pengeluaranpenerimaan', 'keterangan' => 'Laporan Pengeluaran / Penerimaan', 'level' => 3, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 63, 'parent_id' => 52, 'root_id' => 1, 'nama' => 'Harian Detail', 'icon' => '<i class="fa fa-file fa-fw"></i>', 'link' => '/report/hariandetail', 'keterangan' => 'Laporan Cash Flow Harian', 'level' => 3, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 64, 'parent_id' => 52, 'root_id' => 1, 'nama' => 'Harian Detail (Omzet=Penjualan)', 'icon' => '<i class="fa fa-file fa-fw"></i>', 'link' => '/report/hariandetail2', 'keterangan' => 'Laporan Cash Flow Harian', 'level' => 3, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 65, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Poin Member', 'icon' => '<i class="fa fa-vcard fa-fw"></i>', 'link' => 'report/poinmember', 'keterangan' => 'Laporan Poin Member', 'level' => 2, 'urutan' => 6, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 66, 'parent_id' => 7, 'root_id' => 1, 'nama' => 'Potensi Lost Sales', 'icon' => '<i class="fa fa-sort-numeric-desc fa-fw"></i>', 'link' => '/report/pls', 'keterangan' => 'Laporan barang yang akan habis', 'level' => 2, 'urutan' => 7, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 67, 'parent_id' => 9, 'root_id' => 1, 'nama' => 'Cetak Label Rak', 'icon' => '<i class="fa fa-barcode fa-fw"></i>', 'link' => 'tools/cetaklabelrak/index', 'keterangan' => 'Label Barang', 'level' => 2, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 68, 'parent_id' => 9, 'root_id' => 1, 'nama' => 'Cetak Form SO', 'icon' => '<i class="fa fa-check-square-o fa-fw"></i>', 'link' => 'tools/cetakformso/index', 'keterangan' => 'Form Stock Opname Manual', 'level' => 2, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 69, 'parent_id' => 9, 'root_id' => 1, 'nama' => 'Cek Harga', 'icon' => '<i class="fa fa-search fa-fw"></i>', 'link' => 'tools/cekharga/index', 'keterangan' => 'Cek Harga', 'level' => 2, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 70, 'parent_id' => 9, 'root_id' => 1, 'nama' => 'Customer Display', 'icon' => '<i class="fa fa-television fa-fw"></i>', 'link' => 'tools/customerdisplay/index', 'keterangan' => 'Layar untuk customer', 'level' => 2, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 71, 'parent_id' => NULL, 'root_id' => NULL, 'nama' => 'POS', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Menu Kasir', 'level' => 0, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 72, 'parent_id' => 71, 'root_id' => 71, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 1, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 73, 'parent_id' => 71, 'root_id' => 71, 'nama' => 'POS (Poin Of Sales)', 'icon' => '<i class="fa fa-shopping-cart fa-fw"></i>', 'link' => '/pos/index', 'keterangan' => 'Poin Of Sales', 'level' => 1, 'urutan' => 2, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 74, 'parent_id' => 71, 'root_id' => 71, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 3, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 75, 'parent_id' => 71, 'root_id' => 71, 'nama' => 'Customer Display', 'icon' => '<i class="fa fa-television fa-fw"></i>', 'link' => 'tools/customerdisplay/index', 'keterangan' => 'Layar untuk customer', 'level' => 1, 'urutan' => 4, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
            ['id' => 76, 'parent_id' => 71, 'root_id' => 71, 'nama' => '-', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Divider', 'level' => 1, 'urutan' => 5, 'status' => 1, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now],
        ]);

        /* Reserve Row untuk Menu yang akan dibuat/diupdate oleh sistem 
         * Sampai 1000 Rows
         */
        $r = [];
        for ($i = 1; $i < 925; $i++) {
            $r[] = ['parent_id' => NULL, 'root_id' => NULL, 'nama' => 'R', 'icon' => NULL, 'link' => NULL, 'keterangan' => 'Reserved (Untuk Auto Update)', 'level' => 0, 'urutan' => 1, 'status' => 2, 'updated_at' => $now, 'updated_by' => 1, 'created_at' => $now];
        }

        $this->insertMultiple('menu', $r);
    }

    public function safeDown()
    {
        echo "m171004_135042_init_tabel_menu does not support migration down.\n";
        return false;
    }

}
