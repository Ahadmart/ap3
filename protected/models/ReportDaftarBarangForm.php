<?php

/**
 * ReportDaftarBarangForm class.
 * ReportDaftarBarangForm is the data structure for keeping
 * report daftarbarang form data. It is used by the 'daftarbarang' action of 'ReportController'.
 * 
 */
class ReportDaftarBarangForm extends CFormModel
{

    const SORT_BY_BARCODE = 1;
    const SORT_BY_BARCODE_DSC = 2;
    const SORT_BY_NAMA = 3;
    const SORT_BY_NAMA_DSC = 4;
    const SORT_BY_KATEGORI = 5;
    const SORT_BY_KATEGORI_DSC = 6;

    public $kategoriId;
    public $profilId;
    public $hanyaDefault;
    public $rakId;
    public $sortBy0;
    public $sortBy1;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['sortBy0, sortBy1', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['kategoriId, profilId, hanyaDefault, rakId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'kategoriId' => 'Kategori',
            'profilId' => 'Profil / Supplier',
            'hanyaDefault' => 'Hanya Default',
            'rakId' => 'Rak',
            'sortBy0' => 'Sort 1',
            'sortBy1' => 'Sort 2',
        ];
    }

    public function reportDaftarBarang()
    {

        $command = Yii::app()->db->createCommand();
        $command->select("
             
                ");
               
        return $command->queryAll();
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_BARCODE => 'Barcode [a-z]',
            self::SORT_BY_BARCODE_DSC => 'Barcode [z-a]',
            self::SORT_BY_NAMA => 'Nama [a-z]',
            self::SORT_BY_NAMA_DSC => 'Nama [z-a]',
            self::SORT_BY_KATEGORI => 'Kategori [a-z]',
            self::SORT_BY_KATEGORI_DSC => 'Kategori [z-a]',
        ];
    }

    public function listSortBy2()
    {
        return [
            self::SORT_BY_BARCODE => 'Barcode [a-z]',
            self::SORT_BY_BARCODE_DSC => 'Barcode [z-a]',
            self::SORT_BY_NAMA => 'Nama [a-z]',
            self::SORT_BY_NAMA_DSC => 'Nama [z-a]',
            self::SORT_BY_KATEGORI => 'Kategori [a-z]',
            self::SORT_BY_KATEGORI_DSC => 'Kategori [z-a]',
        ];
    }

}
