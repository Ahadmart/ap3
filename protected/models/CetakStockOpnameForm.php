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

    public $rakId;
    public $kategoriId;
    public $sortBy;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('rakId, kategoriId, sortBy', 'required', 'message' => '{attribute} tidak boleh kosong')
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
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'rak' => array(self::BELONGS_TO, 'RakBarang', 'rakId'),
            'kategori' => array(self::BELONGS_TO, 'KategoriBarang', 'kategoriId'),
        );
    }

    public function getNamaRak()
    {
        $rak = RakBarang::model()->findByPk($this->rakId);
        return $rak->nama;
    }

}
