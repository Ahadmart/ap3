<?php

class m150724_135249_init_release_0 extends CDbMigration
{
    /*
      public function up() {

      }

      public function down() {
      echo "m150724_135249_init_release_0 does not support migration down.\n";
      return false;
      }

     */

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp()
    {
        $dbEngine = 'InnoDB';

        $this->createTable('barang', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'barcode' => 'varchar(30) CHARACTER SET utf8 NOT NULL',
            'nama' => 'varchar(45) CHARACTER SET utf8 NOT NULL',
            'kategori_id' => 'int(10) unsigned NOT NULL',
            'satuan_id' => 'int(10) unsigned NOT NULL',
            'rak_id' => 'int(10) unsigned NOT NULL',
            'restock_point' => "int(10) unsigned NOT NULL DEFAULT '0'",
            'restock_level' => "int(10) unsigned NOT NULL DEFAULT '0'",
            'status' => "tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=tidak aktif; 1=aktif;'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            "PRIMARY KEY (`id`)",
            "UNIQUE KEY `barcode_UNIQUE` (`barcode`)",
            "KEY `fk_barang_updatedby_idx` (`updated_by`)",
            "KEY `fk_barang_kategori_idx` (`kategori_id`)",
            "KEY `fk_barang_satuan_idx` (`satuan_id`)",
            "KEY `fk_barang_rak_idx` (`rak_id`)"
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');

        $this->createTable('barang_harga_jual', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'barang_id' => 'int(10) unsigned NOT NULL',
            'harga' => 'decimal(18,2) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_barang_harga_jual_updatedby_idx` (`updated_by`)',
            'KEY `fk_barang_harga_jual_barang_idx` (`barang_id`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->createTable('barang_harga_jual_rekomendasi', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'barang_id' => 'int(10) unsigned NOT NULL',
            'harga' => 'decimal(18,2) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_barang_hjr_updatedby_idx` (`updated_by`)',
            'KEY `fk_barang_harga_jual_rekomendasi_barang_idx` (`barang_id`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->createTable('barang_kategori', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_barang_kategori_updatedby_idx` (`updated_by`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->insertMultiple('barang_kategori', array(
            array('nama' => 'umum', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'wafer', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'biskuit', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'sirup', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'mie', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'kopi', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'isotonik drink', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'makanan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'gula', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'kosmetik', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'perlengkapan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'sabun cuci', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'minuman', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'susu', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'atk', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'elektronik', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'bayi', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'obat nyamuk', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'pecah belah', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'muslim', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'sabun', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'shampo', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'mainan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'pakaian', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'obat', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('barang_rak', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_barang_rak_updatedby_idx` (`updated_by`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->insert('barang_rak', array('nama' => 'Rak 1', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'));

        $this->createTable('barang_satuan', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_barang_satuan_updatedby_idx` (`updated_by`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->insertMultiple('barang_satuan', array(
            array('nama' => 'pcs', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'kg', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Ons', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Kardus', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'pak', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'lusin', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'box', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'set', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'gr', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'ltr', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'ml', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('config', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'nilai' => 'varchar(255) NULL',
            'deskripsi' => 'varchar(1000) DEFAULT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_config_updatedby_idx` (`updated_by`)'
                ), 'ENGINE=' . $dbEngine . '  DEFAULT CHARSET=utf8');

        $this->insertMultiple('config', array(
            array('nama' => 'toko.nama', 'nilai' => 'Toko Mart', 'deskripsi' => 'Nama Toko', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.kode', 'nilai' => '01', 'deskripsi' => 'Kode Toko', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.alamat1', 'nilai' => '', 'deskripsi' => 'Alamat 1', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.alamat2', 'nilai' => '', 'deskripsi' => 'Alamat 2', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.alamat3', 'nilai' => '', 'deskripsi' => 'Alamat 3', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.telp', 'nilai' => '', 'deskripsi' => 'Telp', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'toko.email', 'nilai' => 'toko@mart.com', 'deskripsi' => 'E-mail', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'penjualan.jatuh_tempo', 'nilai' => '7', 'deskripsi' => 'Jatuh tempo pembayaran untuk penjualan (hari)', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'keuangan.saldo_awal', 'nilai' => '0', 'deskripsi' => 'Saldo Awal', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'struk.header1', 'deskripsi' => 'Header 1 struk penjualan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'struk.header2', 'deskripsi' => 'Header 2 struk penjualan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'struk.footer1', 'deskripsi' => 'Footer 1 struk penjualan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'struk.footer2', 'deskripsi' => 'Footer 2 struk penjualan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'pos.autoprint', 'nilai' => '1', 'deskripsi' => 'Langsung print ketika disimpan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('harga_pokok_penjualan', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'pembelian_detail_id' => 'int(10) unsigned NOT NULL',
            'penjualan_detail_id' => 'int(10) unsigned NOT NULL',
            'qty' => 'int(10) unsigned NOT NULL',
            'harga_beli' => 'decimal(18,2) DEFAULT NULL',
            'harga_beli_temp' => "decimal(18,2) DEFAULT NULL COMMENT 'field ini diisi harga beli terakhir jika stok negatif, dan nanti harga_beli diisi jika sudah ada pembelian'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_harga_pokok_penjualan_updatedby_idx` (`updated_by`)',
            'KEY `fk_harga_pokok_penjualan_belidetail_idx` (`pembelian_detail_id`)',
            'KEY `fk_harga_pokok_penjualan_jualdetail_idx` (`penjualan_detail_id`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('hutang_piutang', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nomor' => 'varchar(45) NOT NULL',
            'profil_id' => 'int(10) unsigned NOT NULL',
            'jumlah' => 'decimal(18,2) NOT NULL',
            'tipe' => "tinyint(4) NOT NULL COMMENT '0=hutang; 1=piutang'",
            'status' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=belumlunas; 1=lunas'",
            'asal' => "tinyint(4) NOT NULL COMMENT '1: Pembelian; 2: Retur Beli; 3: Penjualan; 4: Retur Jual'",
            'nomor_dokumen_asal' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nomor` (`nomor`)',
            'KEY `fk_hutang_piutang_updatedby_idx` (`updated_by`)',
            'KEY `fk_hutang_piutang_profil_idx` (`profil_id`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('hutang_piutang_detail', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'hutang_piutang_id' => 'int(10) unsigned NOT NULL',
            'keterangan' => 'varchar(255) NOT NULL',
            'jumlah' => 'decimal(18,2) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_hutangpiutang_detail_header_idx` (`hutang_piutang_id`)',
            'KEY `fk_hutangpiutang_detail_updatedby_idx` (`updated_by`)'
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('inventory_balance', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'asal' => "tinyint(4) NOT NULL COMMENT '1=pembelian; 2=retur jual; 3=so'",
            'nomor_dokumen' => 'varchar(45) DEFAULT NULL',
            'barang_id' => 'int(10) unsigned NOT NULL',
            'harga_beli' => 'decimal(18,2) DEFAULT NULL',
            'qty_awal' => 'int(11) NOT NULL',
            'qty' => 'int(11) NOT NULL',
            'pembelian_detail_id' => 'int(10) unsigned DEFAULT NULL',
            'retur_penjualan_detail_id' => 'int(10) unsigned DEFAULT NULL',
            'stock_opname_detail_id' => 'int(10) unsigned DEFAULT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_inventory_balance_updatedby_idx` (`updated_by`)',
            'KEY `fk_inventory_balance_barang_idx` (`barang_id`)',
            'KEY `fk_inventory_balance_pembeliandetail_idx` (`pembelian_detail_id`)',
            'KEY `fk_inventory_balance_sodetail_idx` (`stock_opname_detail_id`)',
            'KEY `fk_inventory_balance_returjualdetail_idx` (`retur_penjualan_detail_id`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('item_keuangan', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'parent_id' => 'int(10) unsigned DEFAULT NULL',
            'jenis' => "tinyint(4) NOT NULL COMMENT '0=Pengeluaran; 1=Penerimaan'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_item_keuangan_updatedby_idx` (`updated_by`)',
            'KEY `fk_item_keuangan_parent_idx` (`parent_id`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=101');

        $this->insertMultiple('item_keuangan', array(
            array('id' => 1, 'nama' => 'Bayar Hutang', 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 2, 'nama' => 'Pembelian', 'parent_id' => 1, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 3, 'nama' => 'Penerimaan Piutang', 'jenis' => 1, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 4, 'nama' => 'Penjualan', 'jenis' => 1, 'parent_id' => 3, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 5, 'nama' => 'Retur Beli', 'jenis' => 1, 'parent_id' => 3, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 6, 'nama' => 'Retur Jual', 'parent_id' => 1, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 7, 'nama' => 'Expense', 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 8, 'nama' => 'Non Expense', 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 9, 'nama' => 'Pendapatan Lain', 'jenis' => 1, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Listrik', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Alat Tulis Kantor', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Gaji Karyawan', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Internet', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Air Minum', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Sampah', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'RT', 'parent_id' => 7, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Kasbon', 'parent_id' => 8, 'jenis' => 0, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Jual Kardus', 'jenis' => 1, 'parent_id' => 9, 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('jenis_transaksi', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_tipe_pembayaran_updatedby_idx` (`updated_by`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8 AUTO_INCREMENT=3');

        $this->insertMultiple('jenis_transaksi', array(
            array('nama' => 'Tunai', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Transfer', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('jurnal', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nomor' => 'varchar(45) NOT NULL',
            'tanggal' => 'datetime NOT NULL',
            'keterangan' => 'varchar(100) NOT NULL',
            'status' => "tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=draft; 1=saved; 2; posting'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nomor_UNIQUE` (`nomor`)',
            'KEY `fk_jurnal_updatedby_idx` (`updated_by`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('jurnal_detail', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'jurnal_id' => 'int(10) unsigned NOT NULL',
            'kode_akun_id' => 'int(10) unsigned NOT NULL',
            'keterangan' => 'varchar(100) NOT NULL',
            'nomor_dokumen' => 'varchar(45) DEFAULT NULL',
            'debit' => "decimal(10,0) NOT NULL DEFAULT '0'",
            'kredit' => 'decimal(10,0) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'KEY `fk_jurnal_detail_kodeakun_idx` (`kode_akun_id`)',
            'KEY `fk_jurnal_detail_header_idx` (`jurnal_id`)',
            'KEY `fk_jurnal_detail_updatedby_idx` (`updated_by`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('kas_bank', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nama' => 'varchar(45) NOT NULL',
            'kode_akun_id' => 'int(10) unsigned DEFAULT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'KEY `fk_kas_bank_akun_idx` (`kode_akun_id`)',
            'KEY `fk_kas_bank_updatedby_idx` (`updated_by`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insert('kas_bank', array('nama' => 'Kas', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'));
        $this->insert('kas_bank', array('nama' => 'Bank', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'));

        $this->createTable('kode_akun', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'kode' => 'varchar(45) NOT NULL',
            'nama' => 'varchar(45) NOT NULL',
            'parent_id' => 'int(10) unsigned DEFAULT NULL',
            'level' => "tinyint(3) unsigned NOT NULL DEFAULT '0'",
            'trx' => "tinyint(4) NOT NULL DEFAULT '0' COMMENT 'trx=1, Kode akun untuk transaksi aplikasi. Lainnya untuk memorial, dll'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `kode` (`kode`)',
            'KEY `fk_kode_akun_parent_idx` (`parent_id`)',
            'KEY `fk_kode_akun_updatedby_idx` (`updated_by`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('kode_dokumen', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'kode' => "varchar(2) NOT NULL COMMENT '01 Pembelian BL; 02 Retur Pembelian RB; 03 Penjualan JU; 04 Retur Penjualan RJ; 05 SO; 06 Hutang HU; 07 Piutang PU; 08 Pengeluaran OU; 09 Penerimaan IN'",
            'nama' => 'varchar(45) NOT NULL',
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nama` (`nama`)',
            'UNIQUE KEY `kode_UNIQUE` (`kode`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('pembelian', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            'nomor' => 'varchar(45) DEFAULT NULL',
            'tanggal' => 'datetime NOT NULL',
            'profil_id' => 'int(10) unsigned NOT NULL',
            'referensi' => 'varchar(45) DEFAULT NULL',
            'tanggal_referensi' => 'date DEFAULT NULL',
            'hutang_piutang_id' => 'int(10) unsigned DEFAULT NULL',
            'status' => "tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 1:hutang; 2:lunas;'",
            'updated_at' => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
            'updated_by' => 'int(10) unsigned NOT NULL',
            'created_at' => "timestamp NOT NULL DEFAULT '2000-01-01 00:00:00'",
            'PRIMARY KEY (`id`)',
            'UNIQUE KEY `nomor_UNIQUE` (`nomor`)',
            'KEY `fk_pembelian_updatedby_idx` (`updated_by`)',
            'KEY `fk_pembelian_hutangpiutang_idx` (`hutang_piutang_id`)',
            'KEY `fk_pembelian_profil_idx` (`profil_id`)',
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('pembelian_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `pembelian_id` int(10) unsigned NOT NULL,
           `barang_id` int(10) unsigned NOT NULL,
           `qty` int(10) unsigned NOT NULL DEFAULT '1',
           `harga_beli` decimal(18,2) NOT NULL,
           `harga_jual` decimal(18,2) NOT NULL,
           `harga_jual_rekomendasi` decimal(18,2) DEFAULT NULL,
           `tanggal_kadaluwarsa` date DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_pembelian_detail_pemb_idx` (`pembelian_id`),
           KEY `fk_pembelian_detail_barang_idx` (`barang_id`),
           KEY `fk_pembelian_detail_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('penerimaan', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nomor` varchar(45) DEFAULT NULL,
           `tanggal` date NOT NULL,
           `keterangan` varchar(500) DEFAULT NULL,
           `profil_id` int(10) unsigned NOT NULL,
           `kas_bank_id` int(10) unsigned NOT NULL,
           `kategori_id` int(10) unsigned NOT NULL,
           `jenis_transaksi_id` int(10) unsigned NOT NULL DEFAULT '1',
           `referensi` varchar(45) DEFAULT NULL,
           `tanggal_referensi` date DEFAULT NULL,
           `uang_dibayar` decimal(18,2) DEFAULT NULL,
           `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 1:bayar;',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor_UNIQUE` (`nomor`),
           KEY `fk_penerimaan_updatedby_idx` (`updated_by`),
           KEY `fk_penerimaan_kasbank_idx` (`kas_bank_id`),
           KEY `fk_penerimaan_kategori_idx` (`kategori_id`),
           KEY `fk_penerimaan_jenis_idx` (`jenis_transaksi_id`),
           KEY `fk_penerimaan_profil_idx` (`profil_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('penerimaan_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `penerimaan_id` int(10) unsigned NOT NULL,
           `item_id` int(10) unsigned NOT NULL,
           `hutang_piutang_id` int(10) unsigned DEFAULT NULL,
           `keterangan` varchar(255) DEFAULT NULL,
           `jumlah` decimal(18,2) NOT NULL DEFAULT '0.00',
           `posisi` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Debet (+); 1=Kredit (-)',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_penerimaan_detail_updatedby_idx` (`updated_by`),
           KEY `fk_penerimaan_detail_header_idx` (`penerimaan_id`),
           KEY `fk_penerimaan_detail_item_idx` (`item_id`),
           KEY `fk_penerimaan_detail_hp_idx` (`hutang_piutang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('penerimaan_kategori', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nama` varchar(40) NOT NULL,
           `deskripsi` varchar(255) DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nama_UNIQUE` (`nama`),
           KEY `fk_penerimaan_kategori_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('penerimaan_kategori', array(
            array('nama' => 'Penjualan/Retur Beli', 'deskripsi' => 'Transaksi Via Aplikasi', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Pendapatan Lain', 'deskripsi' => 'Pendapatan Lain-lain', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('pengeluaran', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nomor` varchar(45) DEFAULT NULL,
           `tanggal` date NOT NULL,
           `keterangan` varchar(500) DEFAULT NULL,
           `profil_id` int(10) unsigned NOT NULL,
           `kas_bank_id` int(10) unsigned NOT NULL,
           `kategori_id` int(10) unsigned NOT NULL,
           `jenis_transaksi_id` int(10) unsigned NOT NULL DEFAULT '1',
           `referensi` varchar(45) DEFAULT NULL,
           `tanggal_referensi` date DEFAULT NULL,
           `uang_dibayar` decimal(18,2) DEFAULT NULL,
           `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 1:bayar;',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor_UNIQUE` (`nomor`),
           KEY `fk_pengeluaran_updatedby_idx` (`updated_by`),
           KEY `fk_pengeluaran_kategori_idx` (`kategori_id`),
           KEY `fk_pengeluaran_jenistrx_idx` (`jenis_transaksi_id`),
           KEY `fk_pengeluaran_kasbank_idx` (`kas_bank_id`),
           KEY `fk_pengeluaran_profil_idx` (`profil_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('pengeluaran_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `pengeluaran_id` int(10) unsigned NOT NULL,
           `item_id` int(10) unsigned NOT NULL,
           `hutang_piutang_id` int(10) unsigned DEFAULT NULL,
           `keterangan` varchar(255) DEFAULT NULL,
           `jumlah` decimal(18,2) NOT NULL DEFAULT '0.00',
           `posisi` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=Debet (+); 1=Kredit (-)',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_pengeluaran_detail_updatedby_idx` (`updated_by`),
           KEY `fk_pengeluaran_detail_header_idx` (`pengeluaran_id`),
           KEY `fk_pengeluaran_detail_item_idx` (`item_id`),
           KEY `fk_pengeluaran_detail_hp_idx` (`hutang_piutang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('pengeluaran_kategori', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nama` varchar(40) NOT NULL,
           `deskripsi` varchar(255) DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nama_UNIQUE` (`nama`),
           KEY `fk_pengeluaran_kategori_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('pengeluaran_kategori', array(
            array('nama' => 'Pembelian / Retur Jual', 'deskripsi' => 'Transaksi Via Aplikasi', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('nama' => 'Pengeluaran Lainnya', 'deskripsi' => 'Biaya dan pengeluaran lain', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
        ));

        $this->createTable('penjualan', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nomor` varchar(45) DEFAULT NULL,
           `tanggal` datetime NOT NULL,
           `profil_id` int(10) unsigned NOT NULL,
           `hutang_piutang_id` int(10) unsigned DEFAULT NULL,
           `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0:draft; 1:piutang; 2:lunas;',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor_UNIQUE` (`nomor`),
           KEY `fk_penjualan_updatedby_idx` (`updated_by`),
           KEY `fk_penjualan_piutang_idx` (`hutang_piutang_id`),
           KEY `fk_penjualan_profil_idx` (`profil_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('penjualan_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `penjualan_id` int(10) unsigned NOT NULL,
           `barang_id` int(10) unsigned NOT NULL,
           `qty` int(10) unsigned NOT NULL DEFAULT '1',
           `harga_jual` decimal(18,2) NOT NULL,
           `harga_jual_rekomendasi` decimal(18,2) DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_penjualan_detail_updatedby_idx` (`updated_by`),
           KEY `fk_penjualan_detail_header_idx` (`penjualan_id`),
           KEY `fk_penjualan_detail_barang_idx` (`barang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('profil', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `tipe_id` tinyint(3) unsigned NOT NULL COMMENT '1=supplier, 2=customer, 3=karyawan',
           `nama` varchar(100) NOT NULL,
           `alamat1` varchar(100) DEFAULT NULL,
           `alamat2` varchar(100) DEFAULT NULL,
           `alamat3` varchar(100) DEFAULT NULL,
           `telp` varchar(20) DEFAULT NULL,
           `keterangan` varchar(1000) DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_profil_updatedby_idx` (`updated_by`),
           KEY `fk_profil_tipe_idx` (`tipe_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('profil', array(
            array('id' => 1, 'tipe_id' => 1, 'nama' => 'Init', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 2, 'tipe_id' => 2, 'nama' => 'Umum', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00')
        ));

        $this->execute('ALTER TABLE `profil` AUTO_INCREMENT = 101');

        $this->createTable('profil_tipe', array(
            "`id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
           `nama` varchar(45) NOT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nama` (`nama`),
           UNIQUE KEY `nama_UNIQUE` (`nama`),
           KEY `fk_profil_tipe_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('profil_tipe', array(
            array('id' => 1, 'nama' => 'Supplier', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 2, 'nama' => 'Customer', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 3, 'nama' => 'Karyawan', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00')
        ));

        $this->createTable('retur_pembelian', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nomor` varchar(45) DEFAULT NULL,
           `tanggal` datetime NOT NULL,
           `profil_id` int(10) unsigned NOT NULL,
           `hutang_piutang_id` int(10) unsigned DEFAULT NULL,
           `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=draft;1=piutang;2=lunas',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor` (`nomor`),
           KEY `fk_retur_pembelian_updatedby_idx` (`updated_by`),
           KEY `fk_retur_pembelian_supplier_idx` (`profil_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('retur_pembelian_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `retur_pembelian_id` int(10) unsigned NOT NULL,
           `inventory_balance_id` int(10) unsigned NOT NULL,
           `qty` int(10) unsigned NOT NULL DEFAULT '1',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_retur_pembelian_detail_header_idx` (`retur_pembelian_id`),
           KEY `fk_retur_pembelian_detail_updatedby_idx` (`updated_by`),
           KEY `fk_retur_pembelian_detail_inventory_idx` (`inventory_balance_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('retur_penjualan', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `nomor` varchar(45) DEFAULT NULL,
           `tanggal` datetime NOT NULL,
           `profil_id` int(10) unsigned NOT NULL,
           `hutang_piutang_id` int(10) unsigned DEFAULT NULL,
           `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=draft;1=hutang;2=lunas',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor` (`nomor`),
           KEY `fk_retur_penjualan_updatedby_idx` (`updated_by`),
           KEY `fk_retur_penjualan_customer_idx` (`profil_id`),
           KEY `fk_retur_penjualan_hutangpiutang_idx` (`hutang_piutang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('retur_penjualan_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `retur_penjualan_id` int(10) unsigned NOT NULL,
           `penjualan_detail_id` int(10) unsigned NOT NULL,
           `qty` int(10) unsigned NOT NULL,
           `harga_jual` decimal(18,2) NOT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_retur_penjualan_detail_header_idx` (`retur_penjualan_id`),
           KEY `fk_retur_penjualan_detail_updatedby_idx` (`updated_by`),
           KEY `fk_retur_penjualan_detail_pjdetail_idx` (`penjualan_detail_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('stock_opname', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `tanggal` datetime NOT NULL,
           `nomor` varchar(45) DEFAULT NULL,
           `rak_id` int(10) unsigned DEFAULT NULL,
           `keterangan` varchar(500) DEFAULT NULL,
           `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '0=draft; 1=so',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           UNIQUE KEY `nomor` (`nomor`),
           KEY `fk_stock_opname_updatedby_idx` (`updated_by`),
           KEY `fk_stock_opname_rak_idx` (`rak_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('stock_opname_detail', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `stock_opname_id` int(10) unsigned NOT NULL,
           `barang_id` int(10) unsigned NOT NULL,
           `qty_tercatat` int(11) NOT NULL,
           `qty_sebenarnya` int(11) NOT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_stock_opname_detail_updatedby_idx` (`updated_by`),
           KEY `fk_stock_opname_detail_barang_idx` (`barang_id`),
           KEY `fk_stock_opname_detail_header_idx` (`stock_opname_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('supplier_barang', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
           `supplier_id` int(10) unsigned NOT NULL,
           `barang_id` int(10) unsigned NOT NULL,
           `default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1=default',
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_supplier_barang_updatedby_idx` (`updated_by`),
           KEY `fk_supplier_barang_supplier_idx` (`supplier_id`),
           KEY `fk_supplier_barang_barang_idx` (`barang_id`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('theme', array(
            "`id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
           `nama` varchar(255) NOT NULL,
           `deskripsi` varchar(500) DEFAULT NULL,
           `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
           `updated_by` int(10) unsigned NOT NULL,
           `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
           PRIMARY KEY (`id`),
           KEY `fk_theme_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('theme', array(
            array('id' => 1, 'nama' => 'default', 'deskripsi' => 'Default', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 2, 'nama' => 'default_dark', 'deskripsi' => 'Default Dark', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('id' => 3, 'nama' => 'materialize', 'deskripsi' => 'G Material Design', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00')
        ));

        $this->createTable('laporan_harian', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `tanggal` date NOT NULL,
            `saldo_akhir` decimal(18,2) DEFAULT NULL,
            `keterangan` varchar(5000) DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            UNIQUE KEY `tanggal_UNIQUE` (`tanggal`),
            KEY `fk_laporan_harian_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->createTable('device', array(
            "`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `tipe_id` tinyint(4) NOT NULL COMMENT '0=pos client;1=lpr printer;2=plain/text;3=pdf;4=csv',
            `nama` varchar(100) NOT NULL,
            `keterangan` varchar(500) DEFAULT NULL,
            `address` varchar(100) DEFAULT NULL,
            `lf_sebelum` tinyint(4) DEFAULT NULL,
            `lf_setelah` tinyint(4) DEFAULT NULL,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `updated_by` int(10) unsigned NOT NULL,
            `created_at` timestamp NOT NULL DEFAULT '2000-01-01 00:00:00',
            PRIMARY KEY (`id`),
            UNIQUE KEY `nama_UNIQUE` (`nama`),
            KEY `fk_device_updatedby_idx` (`updated_by`)"
                ), 'ENGINE=' . $dbEngine . ' DEFAULT CHARSET=utf8');

        $this->insertMultiple('device', array(
            array('tipe_id' => 0, 'nama' => 'Kasir 1', 'keterangan' => 'Komputer Kasir 1', 'address' => '192.168.1.1', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('tipe_id' => 1, 'nama' => 'printer192.168.1.1', 'keterangan' => 'Printer di Kasir 1', 'address' => '192.168.1.1', 'lf_setelah' => '5', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('tipe_id' => 2, 'nama' => 'Plain/Text', 'keterangan' => 'Export to text file', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('tipe_id' => 3, 'nama' => 'PDF', 'keterangan' => 'Export to PDF', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00'),
            array('tipe_id' => 4, 'nama' => 'CSV', 'keterangan' => 'Export to CSV', 'updated_at' => '2000-01-01 00:00:00', 'updated_by' => 1, 'created_at' => '2000-01-01 00:00:00')
        ));

        /* Foreign Key Tabel barang */
        $this->addForeignKey('fk_barang_kategori', 'barang', 'kategori_id', 'barang_kategori', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_rak', 'barang', 'rak_id', 'barang_rak', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_satuan', 'barang', 'satuan_id', 'barang_satuan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_updatedby', 'barang', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel barang_harga_jual */
        $this->addForeignKey('fk_barang_harga_jual_barang', 'barang_harga_jual', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_harga_jual_updatedby', 'barang_harga_jual', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel barang_harga_jual_rekomendasi */
        $this->addForeignKey('fk_barang_harga_jual_rekomendasi_barang', 'barang_harga_jual_rekomendasi', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_barang_harga_jual_rekomendasi_updatedby', 'barang_harga_jual_rekomendasi', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel barang_kategori */
        $this->addForeignKey('fk_barang_kategori_updatedby', 'barang_kategori', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel barang_rak */
        $this->addForeignKey('fk_barang_rak_updatedby', 'barang_rak', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel barang_satuan */
        $this->addForeignKey('fk_barang_satuan_updatedby', 'barang_satuan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel config */
        $this->addForeignKey('fk_config_updatedby', 'config', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel harga_pokok_penjualan */
        $this->addForeignKey('fk_harga_pokok_penjualan_belidetail', 'harga_pokok_penjualan', 'pembelian_detail_id', 'pembelian_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_harga_pokok_penjualan_jualdetail', 'harga_pokok_penjualan', 'penjualan_detail_id', 'penjualan_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_harga_pokok_penjualan_updatedby', 'harga_pokok_penjualan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel hutang_piutang */
        $this->addForeignKey('fk_hutang_piutang_profil', 'hutang_piutang', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_hutang_piutang_updatedby', 'hutang_piutang', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel hutang_piutang_detail */
        $this->addForeignKey('fk_hutangpiutang_detail_header', 'hutang_piutang_detail', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_hutangpiutang_detail_updatedby', 'hutang_piutang_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel inventory_balance */
        $this->addForeignKey('fk_inventory_balance_barang', 'inventory_balance', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_inventory_balance_pembeliandetail', 'inventory_balance', 'pembelian_detail_id', 'pembelian_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_inventory_balance_returjualdetail', 'inventory_balance', 'retur_penjualan_detail_id', 'retur_penjualan_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_inventory_balance_sodetail', 'inventory_balance', 'stock_opname_detail_id', 'stock_opname_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_inventory_balance_updatedby', 'inventory_balance', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel item_keuangan */
        $this->addForeignKey('fk_item_keuangan_parent', 'item_keuangan', 'parent_id', 'item_keuangan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_item_keuangan_updatedby', 'item_keuangan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel jenis_transaksi */
        $this->addForeignKey('fk_jenis_transaksi_updatedby', 'jenis_transaksi', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel jurnal */
        $this->addForeignKey('fk_jurnal_updatedby', 'jurnal', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel jurnal_detail */
        $this->addForeignKey('fk_jurnal_detail_updatedby', 'jurnal_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel kas_bank */
        $this->addForeignKey('fk_kas_bank_akun', 'kas_bank', 'kode_akun_id', 'kode_akun', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_kas_bank_updatedby', 'kas_bank', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel kode_akun */
        $this->addForeignKey('fk_kode_akun_parent', 'kode_akun', 'parent_id', 'kode_akun', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_kode_akun_updatedby', 'kode_akun', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel pembelian */
        $this->addForeignKey('fk_pembelian_hutangpiutang', 'pembelian', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pembelian_profil', 'pembelian', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pembelian_updatedby', 'pembelian', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel pembelian_detail */
        $this->addForeignKey('fk_pembelian_detail_barang', 'pembelian_detail', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pembelian_detail_header', 'pembelian_detail', 'pembelian_id', 'pembelian', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pembelian_detail_updatedby', 'pembelian_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penerimaan */
        $this->addForeignKey('fk_penerimaan_jenis', 'penerimaan', 'jenis_transaksi_id', 'jenis_transaksi', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_kasbank', 'penerimaan', 'kas_bank_id', 'kas_bank', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_kategori', 'penerimaan', 'kategori_id', 'penerimaan_kategori', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_profil', 'penerimaan', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_updatedby', 'penerimaan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penerimaan_detail */
        $this->addForeignKey('fk_penerimaan_detail_header', 'penerimaan_detail', 'penerimaan_id', 'penerimaan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_detail_hp', 'penerimaan_detail', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_detail_item', 'penerimaan_detail', 'item_id', 'item_keuangan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penerimaan_detail_updatedby', 'penerimaan_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penerimaan_kategori */
        $this->addForeignKey('fk_penerimaan_kategori_updatedby', 'penerimaan_kategori', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel pengeluaran */
        $this->addForeignKey('fk_pengeluaran_jenistrx', 'pengeluaran', 'jenis_transaksi_id', 'jenis_transaksi', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_kasbank', 'pengeluaran', 'kas_bank_id', 'kas_bank', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_kategori', 'pengeluaran', 'kategori_id', 'pengeluaran_kategori', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_profil', 'pengeluaran', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_updatedby', 'pengeluaran', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel pengeluaran_detail */
        $this->addForeignKey('fk_pengeluaran_detail_header', 'pengeluaran_detail', 'pengeluaran_id', 'pengeluaran', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_detail_hp', 'pengeluaran_detail', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_pengeluaran_detail_updatedby', 'pengeluaran_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel pengeluaran_kategori */
        $this->addForeignKey('fk_pengeluaran_kategori_updatedby', 'pengeluaran_kategori', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penjualan */
        $this->addForeignKey('fk_penjualan_piutang', 'penjualan', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_profil', 'penjualan', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_updatedby', 'penjualan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel penjualan_detail */
        $this->addForeignKey('fk_penjualan_detail_barang', 'penjualan_detail', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_detail_header', 'penjualan_detail', 'penjualan_id', 'penjualan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_penjualan_detail_updatedby', 'penjualan_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel profil */
        $this->addForeignKey('fk_profil_tipe', 'profil', 'tipe_id', 'profil_tipe', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_profil_updatedby', 'profil', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel profil_tipe */
        $this->addForeignKey('fk_profil_tipe_updatedby', 'profil_tipe', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel retur_pembelian */
        $this->addForeignKey('fk_retur_pembelian_profil', 'retur_pembelian', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_pembelian_updatedby', 'retur_pembelian', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel retur_pembelian_detail */
        $this->addForeignKey('fk_retur_pembelian_detail_header', 'retur_pembelian_detail', 'retur_pembelian_id', 'retur_pembelian', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_pembelian_detail_inventory', 'retur_pembelian_detail', 'inventory_balance_id', 'inventory_balance', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_pembelian_detail_updatedby', 'retur_pembelian_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel retur_penjualan */
        $this->addForeignKey('fk_retur_penjualan_hutangpiutang', 'retur_penjualan', 'hutang_piutang_id', 'hutang_piutang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_penjualan_profil', 'retur_penjualan', 'profil_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_penjualan_updatedby', 'retur_penjualan', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel retur_penjualan_detail */
        $this->addForeignKey('fk_retur_penjualan_detail_header', 'retur_penjualan_detail', 'retur_penjualan_id', 'retur_penjualan', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_penjualan_detail_pjdetail', 'retur_penjualan_detail', 'penjualan_detail_id', 'penjualan_detail', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_retur_penjualan_detail_updatedby', 'retur_penjualan_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel stock_opname */
        $this->addForeignKey('fk_stock_opname_rak', 'stock_opname', 'rak_id', 'barang_rak', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_stock_opname_updatedby', 'stock_opname', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel stock_opname_detail */
        $this->addForeignKey('fk_stock_opname_detail_barang', 'stock_opname_detail', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_stock_opname_detail_header', 'stock_opname_detail', 'stock_opname_id', 'stock_opname', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_stock_opname_detail_updatedby', 'stock_opname_detail', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel supplier_barang */
        $this->addForeignKey('fk_supplier_barang_barang', 'supplier_barang', 'barang_id', 'barang', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_supplier_barang_supplier', 'supplier_barang', 'supplier_id', 'profil', 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk_supplier_barang_updatedby', 'supplier_barang', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel theme */
        $this->addForeignKey('fk_theme_updatedby', 'theme', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel user */
        $this->addForeignKey('fk_user_theme', 'user', 'theme_id', 'theme', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel laporan_harian */
        $this->addForeignKey('fk_laporan_harian_updatedby', 'laporan_harian', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');

        /* Foreign Key Tabel device */
        $this->addForeignKey('fk_device_updatedby', 'device', 'updated_by', 'user', 'id', 'NO ACTION', 'NO ACTION');
    }

    public function safeDown()
    {
        echo "m150724_135249_init_release_0 does not support migration down.\n";
        return false;
    }

}
