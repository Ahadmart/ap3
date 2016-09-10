<?php

/**
 * ReportUmurBarangForm class.
 * ReportUmurBarangForm is the data structure for keeping
 * report umurbarang form data. It is used by the 'umurbarang' action of 'ReportController'.
 * 
 */
class ReportUmurBarangForm extends CFormModel
{

    const SORT_BY_STOK_ASC = 1;
    const SORT_BY_STOK_DSC = 2;
    const SORT_BY_NILAISTOK_ASC = 3;
    const SORT_BY_NILAISTOK_DSC = 4;
    const SORT_BY_AVGDAILYSALES_ASC = 5;
    const SORT_BY_AVGDAILYSALES_DSC = 6;
    const SORT_BY_UMUR_ASC = 7;
    const SORT_BY_UMUR_DSC = 8;
    /* ============= */
    const OPSI_BULAN_3 = 0;
    const OPSI_BULAN_6 = 1;
    const OPSI_BULAN_12 = 2;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $bulan; // Umur barang dalam bulan
    public $dari;
    public $sampai;
    public $kategoriId;
    public $limit = 200;
    public $sortBy0;
    public $sortBy1;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['limit, sortBy0, sortBy1', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['bulan, dari, sampai, kategoriId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'bulan' => 'Umur (Kosongkan jika memilih tanggal !)',
            'kategoriId' => 'Kategori',
            'sortBy0' => 'Sort 1',
            'sortBy1' => 'Sort 2',
        ];
    }

    public function reportUmurBarang()
    {
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');
        if (empty($this->bulan)) {
            $whereBulan = "inventory_balance.created_at BETWEEN :dari AND :sampai";
        } else {
            $whereBulan = "TIMESTAMPDIFF(MONTH, inventory_balance.created_at, NOW()) >= :bulan";
        }
        $kategoriQuery = '';
        if (!empty($this->kategoriId)) {
            $kategoriQuery = 'JOIN barang ON inventory_balance.barang_id = barang.id
                                AND barang.kategori_id = :kategoriId';
        }
        $command = Yii::app()->db->createCommand();
        $command->select("
                t_inventory.*,
                SUM(ib.qty) total_stok,
                TIMESTAMPDIFF(MONTH, tgl_beli_awal, NOW()) umur_bulan,
                TIMESTAMPDIFF(DAY, tgl_beli_awal, NOW()) umur_hari,
                barang.barcode,
                barang.nama
                ");
        $command->from("
                (SELECT 
                    barang_id,
                        SUM(qty) qty,
                        SUM(qty * harga_beli) nominal,
                        MIN(inventory_balance.created_at) tgl_beli_awal,
                        COUNT(*) count
                FROM
                    inventory_balance
        {$kategoriQuery}    
                WHERE
        {$whereBulan}
                        AND qty > 0
                GROUP BY barang_id
                LIMIT {$this->limit}) AS t_inventory
                ");
        $command->join('inventory_balance ib', 't_inventory.barang_id = ib.barang_id');
        $command->join('barang', 't_inventory.barang_id = barang.id');
        $command->group('barang_id');
        $command->order([$this->listSortBy2()[$this->sortBy0], $this->listSortBy2()[$this->sortBy1]]);
        if (!empty($this->kategoriId)) {
            $command->bindValue(':kategoriId', $this->kategoriId);
        }

        if (empty($this->bulan)) {
            $command->bindValue(':dari', $dari);
            $command->bindValue(':sampai', $sampai);
        } else {
            $command->bindValue(':bulan', $this->opsiUmurBulan2()[$this->bulan]);
        }

        return $command->queryAll();
    }

    public function filterKategori()
    {
        return ['NULL' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function opsiUmurBulan()
    {
        return [
            self::OPSI_BULAN_3 => '>= 3 Bulan',
            self::OPSI_BULAN_6 => '>= 6 Bulan',
            self::OPSI_BULAN_12 => '>= 1 Tahun',
        ];
    }

    public function opsiUmurBulan2()
    {
        return [
            self::OPSI_BULAN_3 => '3',
            self::OPSI_BULAN_6 => '6',
            self::OPSI_BULAN_12 => '12',
        ];
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_STOK_ASC => 'Stok [a-z]',
            self::SORT_BY_STOK_DSC => 'Stok [z-a]',
            self::SORT_BY_NILAISTOK_ASC => 'Nilai Stok [a-z]',
            self::SORT_BY_NILAISTOK_DSC => 'Nilai Stok [z-a]',
//            self::SORT_BY_AVGDAILYSALES_ASC => 'Rata-rata Penjualan Harian [a-z]',
//            self::SORT_BY_AVGDAILYSALES_DSC => 'Rata-rata Penjualan Harian [z-a]',
            self::SORT_BY_UMUR_ASC => 'Umur [a-z]',
            self::SORT_BY_UMUR_DSC => 'Umur [z-a]',
        ];
    }

    public function listSortBy2()
    {
        return [
            self::SORT_BY_STOK_ASC => 'qty',
            self::SORT_BY_STOK_DSC => 'qty desc',
            self::SORT_BY_NILAISTOK_ASC => 'nominal',
            self::SORT_BY_NILAISTOK_DSC => 'nominal desc',
//            self::SORT_BY_AVGDAILYSALES_ASC => 'Rata-rata Penjualan Harian [a-z]',
//            self::SORT_BY_AVGDAILYSALES_DSC => 'Rata-rata Penjualan Harian [z-a]',
            self::SORT_BY_UMUR_ASC => 'tgl_beli_awal desc',
            self::SORT_BY_UMUR_DSC => 'tgl_beli_awal',
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
