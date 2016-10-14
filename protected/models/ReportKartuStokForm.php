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
    public $dari;
    public $sampai;
    public $sortBy;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['barangId, dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['barangId', 'length', 'max' => 10],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'barangId' => 'Barang',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'sortBy' => 'Urut berdasarkan',
        );
    }


    public function reportKartuStok()
    {
        $command = Yii::app()->db->createCommand();
        $command->select("*");
        $command->from("
            (SELECT 
                5 tipe,
                    (sd.qty_sebenarnya - sd.qty_tercatat) qty,
                    0 harga_beli,
                    so.nomor,
                    so.tanggal
            FROM
                stock_opname_detail sd
            JOIN stock_opname so ON sd.stock_opname_id = so.id
            WHERE
                sd.barang_id = :barangId
                    AND sd.qty_sebenarnya != sd.qty_tercatat UNION SELECT 
                1 tipe,
                    pd.qty,
                    pd.harga_beli,
                    pembelian.nomor,
                    pembelian.tanggal
            FROM
                pembelian_detail pd
            JOIN pembelian ON pd.pembelian_id = pembelian.id
            WHERE
                pd.barang_id = :barangId UNION SELECT 
                2 tipe, rd.qty, ib.harga_beli, retur.nomor, retur.tanggal
            FROM
                retur_pembelian_detail rd
            JOIN inventory_balance ib ON rd.inventory_balance_id = ib.id
                AND ib.barang_id = :barangId
            JOIN retur_pembelian retur ON rd.retur_pembelian_id = retur.id UNION SELECT 
                3 tipe,
                    hpp.qty,
                    hpp.harga_beli,
                    penjualan.nomor,
                    penjualan.tanggal
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN penjualan ON pd.penjualan_id = penjualan.id UNION SELECT 
                4 tipe, rd.qty, 0 harga_beli, retur.nomor, retur.tanggal
            FROM
                retur_penjualan_detail rd
            JOIN penjualan_detail pd ON rd.penjualan_detail_id = pd.id
                AND pd.barang_id = :barangId
            JOIN retur_penjualan retur ON rd.retur_penjualan_id = retur.id) t1");
        
        $command->order("tanggal" . $this->listNamaSortBy()[$this->sortBy]);

        $command->bindValues([
            ':barangId' => $this->barangId,
        ]);

        return $command->queryAll();
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
