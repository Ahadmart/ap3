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

    const LAYOUT_DEFAULT_3 = 10;
    const LAYOUT_DEFAULT_33 = 11;
    const LAYOUT_BANDED = 20;
    const LAYOUT_AHAD_WARNA = 30;
    /* ===================== */
    const KERTAS_LETTER = 10;
    const KERTAS_LETTER_LANDSCAPE = 11;
    const KERTAS_A4 = 20;
    const KERTAS_A4_LANDSCAPE = 21;
    const KERTAS_FOLIO = 30;
    const KERTAS_FOLIO_LANDSCAPE = 31;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA = 'A4';
    const KERTAS_LETTER_LANDSCAPE_NAMA = 'Letter-L';
    const KERTAS_A4_LANDSCAPE_NAMA = 'A4-L';
    const KERTAS_FOLIO_NAMA = 'Folio';
    const KERTAS_FOLIO_LANDSCAPE_NAMA = 'Folio-L';

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

    public static function listLayout()
    {
        return array(
            self::LAYOUT_DEFAULT_3 => 'Default, Tinggi 3 cm',
//            self::LAYOUT_DEFAULT_33 => 'Default, Tinggi 3,3 cm'
            self::LAYOUT_AHAD_WARNA => 'Ahadmart Warna (Tinggi 3cm)'
        );
    }

    public static function listView()
    {
        return [
            self::LAYOUT_DEFAULT_3 => '_label_rak_default_3_pdf',
            self::LAYOUT_AHAD_WARNA => '_label_rak_ahad_warna_pdf'
        ];
    }

    public static function listNamaKertas()
    {
        return array(
            self::KERTAS_A4 => self::KERTAS_A4_NAMA,
            self::KERTAS_A4_LANDSCAPE => self::KERTAS_A4_LANDSCAPE_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
            self::KERTAS_LETTER_LANDSCAPE => self::KERTAS_LETTER_LANDSCAPE_NAMA,
            self::KERTAS_FOLIO => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_FOLIO_LANDSCAPE => self::KERTAS_FOLIO_LANDSCAPE_NAMA
        );
    }

    public static function listKertas()
    {
        return array(
            self::KERTAS_A4 => 'A4',
            self::KERTAS_A4_LANDSCAPE => 'A4 Landscape',
            self::KERTAS_LETTER => 'Letter',
            self::KERTAS_LETTER_LANDSCAPE => 'Letter Landscape',
            self::KERTAS_FOLIO => 'Folio',
            self::KERTAS_FOLIO_LANDSCAPE => 'Folio Landscape'
        );
    }

}
