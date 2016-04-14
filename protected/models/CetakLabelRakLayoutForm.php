<?php

/**
 * CetakLabelRakLayoutForm class.
 * CetakLabelRakLayoutForm is the data structure for keeping
 * Cetak Label Rak form data. It is used by the 'index' action of 'CetaklabelrakController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class CetakLabelRakLayoutForm extends CFormModel
{

    const LAYOUT_NORMAL_3 = 10;
    const LAYOUT_NORMAL_33 = 11;
    const LAYOUT_BANDED = 20;
    /* ===================== */
    const KERTAS_LETTER_POTRAIT = 10;
    const KERTAS_A4_POTRAIT = 20;

    public $layoutId;
    public $kertasId;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('layoutId, kertasId', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('layoutId, kertasId', 'numerical', 'integerOnly' => true),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'layoutId' => 'Layout Label',
            'kertasId' => 'Kertas',
        );
    }

    public function listLayout()
    {
        return array(
            self::LAYOUT_NORMAL_3 => 'Normal, Tinggi 3 cm',
            self::LAYOUT_NORMAL_33 => 'Normal, Tinggi 3,3 cm'
        );
    }

    public function listKertas()
    {
        return array(
            self::KERTAS_A4_POTRAIT => 'A4',
            self::KERTAS_LETTER_POTRAIT => 'Letter'
        );
    }
    
}
