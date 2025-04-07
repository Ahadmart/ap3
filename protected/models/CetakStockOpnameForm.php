<?php

/**
 * CetakStockOpnameForm class.
 * CetakStockOpnameForm is the data structure for keeping
 * Cetak stock opname form data. It is used by the 'index' action of 'CetakformsoController'.
 * 
 * The followings are the available model relations:
 */
class CetakStockOpnameForm extends CFormModel
{

    const SORT_BY_NAMA_ASC = 1;
    const SORT_BY_NAMA_DSC = 2;
    const SORT_BY_BARCODE_ASC = 3;
    const SORT_BY_BARCODE_DSC = 4;
    const SORT_BY_STOK_ASC = 5;
    const SORT_BY_STOK_DSC = 6;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4 = 20;
    const KERTAS_FOLIO = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_FOLIO_NAMA = 'Folio';

    public $rakId;
    public $kategoriId;
    public $sortBy;
    public $kertas;
    public $kecualiStokNol = 1;
    public $aktifSaja = 1;
    public $filterNama;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('rakId, kategoriId, sortBy, kertas', 'required', 'message' => '{attribute} tidak boleh kosong'),
            ['kecualiStokNol, aktifSaja', 'numerical', 'integerOnly' => true],
            ['kecualiStokNol, aktifSaja, filterNama', 'safe'],
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'rakId' => 'Rak',
            'kategoriId' => 'Kategori',
            'sortBy' => 'Sort by',
            'kertas' => 'Kertas',
            'kecualiStokNol' => 'Kecuali Stok 0',
            'aktifSaja' => 'Barang Aktif Saja',
            'filterNama' => 'Nama Barang (sebagian)'
        );
    }

    public function getNamaRak()
    {
        $rak = RakBarang::model()->findByPk($this->rakId);
        return is_null($rak) ? NULL : $rak->nama;
    }

    public function getNamaKategori()
    {
        $kategori = KategoriBarang::model()->findByPk($this->kategoriId);
        return is_null($kategori) ? NULL : $kategori->nama;
    }

    public static function getKategoriRak($id)
    {
        $kategori = Yii::app()->db->createCommand()
                ->selectDistinct('kategori_id, kat.nama')
                ->from(Barang::model()->tableName() . ' bar')
                ->join(KategoriBarang::model()->tableName() . ' kat', 'bar.kategori_id = kat.id')
                ->where('rak_id =:rakId')
                ->order('kat.nama')
                ->bindValue(':rakId', $id)
                ->queryAll();
        $arr = [];
        foreach ($kategori as $kat) {
            $arr[$kat['kategori_id']] = $kat['nama'];
        }
        return $arr;
    }

    public static function listOfSortBy()
    {
        return [
            self::SORT_BY_NAMA_ASC => 'Nama Barang [a-z]',
            self::SORT_BY_NAMA_DSC => 'Nama Barang [z-a]',
            self::SORT_BY_BARCODE_ASC => 'Barcode [a-z]',
            self::SORT_BY_BARCODE_DSC => 'Barcode [z-a]',
            self::SORT_BY_STOK_ASC => 'Stok [a-z]',
            self::SORT_BY_STOK_DSC => 'Stok [z-a]'
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

    public function data()
    {
        $where = "
            WHERE
                barang.rak_id = :rakId
            ";
        if (ctype_digit($this->kategoriId) && $this->kategoriId > 0) {
            $where .= " AND kategori_id = :kategoriId";
        }
        if ($this->kecualiStokNol != 0) {
            $where .= " AND stok != 0";
        }
        if ($this->aktifSaja != 0) {
            $where .= " AND barang.status=:statusAktif";
        }
        if (!empty($this->filterNama) || $this->filterNama != ''){
            $where .= " AND barang.nama like :filterNama";
        }

        $order = '';
        switch ($this->sortBy) {
            case self::SORT_BY_NAMA_ASC:
                $order = 'ORDER BY nama';
                break;
            case self::SORT_BY_NAMA_DSC:
                $order = 'ORDER BY nama desc';
                break;
            case self::SORT_BY_BARCODE_ASC:
                $order = 'ORDER BY barcode';
                break;
            case self::SORT_BY_BARCODE_DSC:
                $order = 'ORDER BY barcode desc';
                break;
            case self::SORT_BY_STOK_ASC:
                $order = 'ORDER BY stok';
                break;
            case self::SORT_BY_STOK_DSC:
                $order = 'ORDER BY stok desc';
                break;
        }

        $sql = "
            SELECT 
                barang.barcode, barang.nama, t_stok.stok, hj.harga, barang.status
            FROM
                barang
                    JOIN
                (SELECT 
                    barang_id, SUM(qty) stok
                FROM
                    inventory_balance
                GROUP BY barang_id) t_stok ON t_stok.barang_id = barang.id
                    JOIN
                (SELECT 
                    barang_id, MAX(id) max_id
                FROM
                    barang_harga_jual
                GROUP BY barang_id) t_hj_max ON t_hj_max.barang_id = barang.id
                    JOIN
                barang_harga_jual hj ON hj.id = t_hj_max.max_id
            {$where}
            {$order}
            ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(':rakId', $this->rakId);
        if (ctype_digit($this->kategoriId)  && $this->kategoriId > 0) {
            $command->bindValue(':kategoriId', $this->kategoriId);
        }
        if ($this->aktifSaja != 0) {
            $command->bindValue(':statusAktif', Barang::STATUS_AKTIF);
        }
        if (!empty($this->filterNama) || $this->filterNama != ''){
            $command->bindValue(':filterNama', "%{$this->filterNama}%");
        }
        // Yii::log($command->getText());
        return $command->queryAll();
    }

    public static function listNamaKertas()
    {
        return array(
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
        );
    }

}
