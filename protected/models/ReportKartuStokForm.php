<?php

/**
 * ReportKartuStokForm class.
 * ReportKartuStokForm is the data structure for keeping
 * report Kartu Stok form data. It is used by the 'kartustok' action of 'ReportController'.
 *
 * The followings are the available model relations:
 */
class ReportKartuStokForm extends CFormModel
{

    const SORT_BY_TANGGAL_ASC = 1;
    const SORT_BY_TANGGAL_DSC = 2;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $barangId;
    public $barcode;
    public $dari;
    public $sampai;
    public $sortBy;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['barcode, dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['barangId', 'length', 'max' => 10],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'barangId' => 'Barang',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'sortBy' => 'Urut berdasarkan',
        ];
    }

    public function getNamaBarang()
    {
        $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $this->barcode]);
        if (!is_null($barang)) {
            return $barang->nama;
        }
    }

    public function tempTableName()
    {
        return 'mem_kartu_stok';
    }

    public function reportKartuStok()
    {
        if (!empty($this->barcode)) {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $this->barcode]);
            if (!is_null($barang)) {
                $this->barangId = $barang->id;
            }
        }
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');

        $tempTableName = $this->tempTableName();

        // Untuk Retur Pembelian Piutang dan Lunas yang diambil created_at bukan tanggal, untuk mendapatkan tanggal perubahan terakhir
        // (field tanggal adalah tanggal ketika dapat nomor retur pembelian)

        $sqlSelect = "
            SELECT 
                *
            FROM
                (SELECT 
                    sd.id,
                    :kodeSo kode,
                    (sd.qty_sebenarnya - sd.qty_tercatat) qty,
                    0 harga_beli,
                    so.nomor,
                    so.tanggal,
                    '' profil
            FROM
                stock_opname_detail sd
            JOIN stock_opname so ON sd.stock_opname_id = so.id AND so.status != :draftSo AND DATE_FORMAT(so.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            WHERE
                sd.barang_id = :barangId
                    /* AND sd.qty_sebenarnya != sd.qty_tercatat (Yang selisih 0 tetap ditampilkan agar terlihat proses SO telah dilakukan) */
                    UNION SELECT 
                    pd.id,
                    :kodePembelian kode,
                    pd.qty,
                    pd.harga_beli,
                    pembelian.nomor,
                    pembelian.tanggal,
                    profil.nama
            FROM
                pembelian_detail pd
            JOIN pembelian ON pd.pembelian_id = pembelian.id AND pembelian.status != :draftPembelian AND DATE_FORMAT(pembelian.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            JOIN profil ON profil.id = pembelian.profil_id
            WHERE
                pd.barang_id = :barangId UNION SELECT 
                rd.id, 
                :kodeReturPembelian kode, 
                rd.qty, 
                ib.harga_beli, 
                retur.nomor, 
                CASE
                    WHEN `retur`.status = :returBeliPiutang OR `retur`.status = :returBeliLunas THEN `retur`.updated_at
                    ELSE `retur`.tanggal
                END,
                profil.nama
            FROM
                retur_pembelian_detail rd
            JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                AND ib.barang_id = :barangId
            JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id AND retur.status != :draftReturPembelian
                AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            JOIN profil ON profil.id = `retur`.profil_id 
            UNION SELECT 
                    hpp.id,
                    :kodePenjualan tipe,
                    hpp.qty,
                    hpp.harga_beli,
                    penjualan.nomor,
                    penjualan.tanggal,
                    profil.nama
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN penjualan ON pd.penjualan_id = penjualan.id AND penjualan.status != :draftPenjualan 
                AND DATE_FORMAT(penjualan.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            JOIN profil ON profil.id = penjualan.profil_id 
            UNION SELECT 
                rd.id, 
                :kodeReturPenjualan tipe, 
                rd.qty, 
                0 harga_beli, 
                retur.nomor, 
                retur.tanggal,
                profil.nama
            FROM
                retur_penjualan_detail rd
            JOIN penjualan_detail pd ON rd.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN retur_penjualan retur ON rd.retur_penjualan_id = retur.id AND retur.status != :draftReturPenjualan
                AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai 
            JOIN profil ON profil.id = `retur`.profil_id    
            UNION SELECT 
                rd.id, :kodeReturPembelianBatal kode, -(rd.qty), ib.harga_beli, retur.nomor, retur.updated_at, profil.nama
            FROM
                retur_pembelian_detail rd
            JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                AND ib.barang_id = :barangId
            JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id AND retur.status = :batalReturPembelian
                AND DATE_FORMAT(retur.updated_at, '%Y-%m-%d') BETWEEN :dari AND :sampai
            JOIN profil ON profil.id = `retur`.profil_id) t1
            ORDER BY tanggal            
                ";
        $sql = " 
            CREATE TEMPORARY TABLE IF NOT EXISTS 
            {$tempTableName} (
              `local_id` int(10) UNSIGNED DEFAULT NULL,
              `tipe` varchar(45) DEFAULT NULL,
              `qty` int(11) DEFAULT NULL,
              `harga_beli` decimal(18,2) DEFAULT NULL,
              `nomor` varchar(45) DEFAULT NULL,
              `tanggal` datetime DEFAULT NULL,
              `profil` varchar(512) DEFAULT NULL)
              ENGINE=MEMORY 
            AS (
            {$sqlSelect}
            )    ";

        Yii::app()->db->createCommand("DROP TEMPORARY TABLE IF EXISTS {$tempTableName}")->execute();
        $command = Yii::app()->db->createCommand($sql);
        $command->execute([
            ':barangId' => $this->barangId,
            ':kodeSo' => KodeDokumen::SO,
            ':kodePembelian' => KodeDokumen::PEMBELIAN,
            ':kodeReturPembelian' => KodeDokumen::RETUR_PEMBELIAN,
            ':kodeReturPembelianBatal' => KodeDokumen::RETUR_PEMBELIAN,
            ':kodePenjualan' => KodeDokumen::PENJUALAN,
            ':kodeReturPenjualan' => KodeDokumen::RETUR_PENJUALAN,
            ':dari' => $dari,
            ':sampai' => $sampai,
            ':draftSo' => StockOpname::STATUS_DRAFT,
            ':draftPembelian' => Pembelian::STATUS_DRAFT,
            ':draftReturPembelian' => ReturPembelian::STATUS_DRAFT,
            ':batalReturPembelian' => ReturPembelian::STATUS_BATAL,
            ':draftPenjualan' => Penjualan::STATUS_DRAFT,
            ':draftReturPenjualan' => ReturPenjualan::STATUS_DRAFT,
            ':returBeliPiutang' => ReturPembelian::STATUS_PIUTANG,
            ':returBeliLunas' => ReturPembelian::STATUS_LUNAS,
        ]);

        $com = Yii::app()->db->createCommand()
            ->from($tempTableName);

        $comBalance = Yii::app()->db->createCommand("
            SELECT 
                SUM(qty) total
            FROM
                (SELECT 
                    5 tipe, SUM(sd.qty_sebenarnya - sd.qty_tercatat) qty
                FROM
                    stock_opname_detail sd
                JOIN stock_opname so ON sd.stock_opname_id = so.id AND so.status != :draftSo AND DATE_FORMAT(so.tanggal, '%Y-%m-%d') < :dari
                WHERE
                    sd.barang_id = :barangId
                        AND sd.qty_sebenarnya != sd.qty_tercatat UNION SELECT 
                    1 tipe, SUM(pd.qty)
                FROM
                    pembelian_detail pd
                JOIN pembelian ON pd.pembelian_id = pembelian.id AND pembelian.status != :draftPembelian AND DATE_FORMAT(pembelian.tanggal, '%Y-%m-%d') < :dari
                WHERE
                    pd.barang_id = :barangId UNION SELECT 
                    2 tipe, 0-SUM(rd.qty) qty
                FROM
                    retur_pembelian_detail rd
                JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                    AND ib.barang_id = :barangId
                JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id AND retur.status != :draftReturPembelian
                    AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') < :dari
                UNION SELECT 
                    3 tipe, 0-SUM(hpp.qty) qty
                FROM
                    harga_pokok_penjualan hpp
                JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                    AND pd.barang_id = :barangId
                JOIN penjualan ON pd.penjualan_id = penjualan.id AND penjualan.status != :draftPenjualan 
                    AND DATE_FORMAT(penjualan.tanggal, '%Y-%m-%d') < :dari
                UNION SELECT 
                    4 tipe, SUM(rd.qty) qty
                FROM
                    retur_penjualan_detail rd
                JOIN penjualan_detail pd ON rd.penjualan_detail_id = pd.id
                    AND pd.barang_id = :barangId
                JOIN retur_penjualan retur ON rd.retur_penjualan_id = retur.id AND retur.status != :draftReturPenjualan
                    AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') < :dari UNION SELECT 
                    5 tipe, 0-SUM(rd.qty) qty
                FROM
                    retur_pembelian_detail rd
                JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                    AND ib.barang_id = :barangId
                JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id AND retur.status = :batalReturPembelian
                    AND DATE_FORMAT(retur.updated_at, '%Y-%m-%d') < :dari) AS t1 
                 ")->queryRow(true, [
            ':dari' => $dari,
            ':barangId' => $this->barangId,
            ':draftSo' => StockOpname::STATUS_DRAFT,
            ':draftPembelian' => Pembelian::STATUS_DRAFT,
            ':draftReturPembelian' => ReturPembelian::STATUS_DRAFT,
            ':batalReturPembelian' => ReturPembelian::STATUS_BATAL,
            ':draftPenjualan' => Penjualan::STATUS_DRAFT,
            ':draftReturPenjualan' => ReturPenjualan::STATUS_DRAFT
        ]);

        $report = [
            'balance' => $comBalance['total'],
            'detail' => $com->queryAll()
        ];

        return $report;
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_TANGGAL_ASC => 'Tanggal [a-z]',
            self::SORT_BY_TANGGAL_DSC => 'Tanggal [z-a]',
        ];
    }

    public function listNamaSortBy()
    {
        return [
            self::SORT_BY_TANGGAL_ASC => 'asc',
            self::SORT_BY_TANGGAL_DSC => 'desc',
        ];
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA
        ];
    }
}
