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
                    so.tanggal
            FROM
                stock_opname_detail sd
            JOIN stock_opname so ON sd.stock_opname_id = so.id AND DATE_FORMAT(so.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            WHERE
                sd.barang_id = :barangId
                    AND sd.qty_sebenarnya != sd.qty_tercatat UNION SELECT 
                    pd.id,
                    :kodePembelian kode,
                    pd.qty,
                    pd.harga_beli,
                    pembelian.nomor,
                    pembelian.tanggal
            FROM
                pembelian_detail pd
            JOIN pembelian ON pd.pembelian_id = pembelian.id AND DATE_FORMAT(pembelian.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            WHERE
                pd.barang_id = :barangId UNION SELECT 
                rd.id, :kodeReturPembelian kode, rd.qty, ib.harga_beli, retur.nomor, retur.tanggal
            FROM
                retur_pembelian_detail rd
            JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                AND ib.barang_id = :barangId
            JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id 
                AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            UNION SELECT 
                    hpp.id,
                    :kodePenjualan tipe,
                    hpp.qty,
                    hpp.harga_beli,
                    penjualan.nomor,
                    penjualan.tanggal
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN penjualan ON pd.penjualan_id = penjualan.id 
                AND DATE_FORMAT(penjualan.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            UNION SELECT 
                rd.id, :kodeReturPenjualan tipe, rd.qty, 0 harga_beli, retur.nomor, retur.tanggal
            FROM
                retur_penjualan_detail rd
            JOIN penjualan_detail pd ON rd.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN retur_penjualan retur ON rd.retur_penjualan_id = retur.id
                AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai) t1
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
              `tanggal` datetime DEFAULT NULL)
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
            ':kodePenjualan' => KodeDokumen::PENJUALAN,
            ':kodeReturPenjualan' => KodeDokumen::RETUR_PENJUALAN,
            ':dari' => $dari,
            ':sampai' => $sampai
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
                JOIN stock_opname so ON sd.stock_opname_id = so.id AND DATE_FORMAT(so.tanggal, '%Y-%m-%d') < :dari
                WHERE
                    sd.barang_id = :barangId
                        AND sd.qty_sebenarnya != sd.qty_tercatat UNION SELECT 
                    1 tipe, SUM(pd.qty)
                FROM
                    pembelian_detail pd
                JOIN pembelian ON pd.pembelian_id = pembelian.id AND DATE_FORMAT(pembelian.tanggal, '%Y-%m-%d') < :dari
                WHERE
                    pd.barang_id = :barangId UNION SELECT 
                    2 tipe, 0-SUM(rd.qty) qty
                FROM
                    retur_pembelian_detail rd
                JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                    AND ib.barang_id = :barangId
                JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id 
                    AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') < :dari
                UNION SELECT 
                    3 tipe, 0-SUM(hpp.qty) qty
                FROM
                    harga_pokok_penjualan hpp
                JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                    AND pd.barang_id = :barangId
                JOIN penjualan ON pd.penjualan_id = penjualan.id 
                    AND DATE_FORMAT(penjualan.tanggal, '%Y-%m-%d') < :dari
                UNION SELECT 
                    4 tipe, SUM(rd.qty) qty
                FROM
                    retur_penjualan_detail rd
                JOIN penjualan_detail pd ON rd.penjualan_detail_id = pd.id
                    AND pd.barang_id = :barangId
                JOIN retur_penjualan retur ON rd.retur_penjualan_id = retur.id
                    AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') < :dari) AS t1
                 ")->queryRow(true, [':dari' => $dari, 'barangId' => $this->barangId]);

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

    public function listKertas()
    {
        return [
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA
        ];
    }

}
