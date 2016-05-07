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

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('rakId, kategoriId, sortBy, kertas', 'required', 'message' => '{attribute} tidak boleh kosong')
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
            'kertas' => 'Kertas'
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

    public function getKategoriRak($id)
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

    public function listOfSortBy()
    {
        return [
            self::SORT_BY_NAMA_ASC => 'Nama Barang [a-z]',
            self::SORT_BY_NAMA_DSC => 'Nama Barang [z-a]',
            self::SORT_BY_BARCODE_ASC => 'Barcode [a-z]',
            self::SORT_BY_BARCODE_DSC => 'Barcode [z-a]'
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

    public function dataForm()
    {
        $cr = new CDbCriteria;
        $cr->condition = 'rak_id = :rakId';
        $cr->params = [':rakId' => $this->rakId];
        if ($this->kategoriId > 0) {
            $cr->addCondition('kategori_id = :kategoriId');
            $cr->params = [
                ':kategoriId' => $this->kategoriId,
                ':rakId' => $this->rakId
            ];
        }
        switch ($this->sortBy) {
            case self::SORT_BY_NAMA_ASC:
                $cr->order='nama';
                break;
            case self::SORT_BY_NAMA_DSC:
                $cr->order='nama desc';
                break;
            case self::SORT_BY_BARCODE_ASC:
                $cr->order='barcode';
                break;
            case self::SORT_BY_BARCODE_DSC:
                $cr->order='barcode desc';
                break;
        }
        return Barang::model()->findAll($cr);
    }

    public function listNamaKertas()
    {
        return array(
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
        );
    }

}
