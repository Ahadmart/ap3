<?php

/**
 * CetakLabelRakForm class.
 * CetakLabelRakForm is the data structure for keeping
 * Cetak Label Rak form data. It is used by the 'index' action of 'CetaklabelrakController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class CetakLabelRakForm extends CFormModel
{

    public $profilId;
    public $rakId;
    public $dari;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('profilId, rakId, dari', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'profilId' => 'Profil Supplier',
            'rakId' => 'User',
            'dari' => 'Harga jual berubah dari',
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'profil' => array(self::BELONGS_TO, 'Profil', 'profilId'),
            'rak' => array(self::BELONGS_TO, 'Rak', 'rakId'),
        );
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaRak()
    {
        $rak = RakBarang::model()->findByPk($this->rakId);
        return $rak->nama;
    }
    
    public function inputBarangKeCetak(){
        
    }

}
