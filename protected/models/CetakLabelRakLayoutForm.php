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
    const LAYOUT_DEFAULT_3             = 10;
    const LAYOUT_DEFAULT_33            = 11;
    const LAYOUT_4X3                   = 12;
    const LAYOUT_MULTI_HARGA           = 20;
    const LAYOUT_MULTI_HARGA_TOTAL     = 21;
    const LAYOUT_MULTI_HARGA_TOTAL_BIG = 22;
    const LAYOUT_AHAD_WARNA            = 30;
    /* ===================== */
    const KERTAS_LETTER           = 10;
    const KERTAS_LETTER_LANDSCAPE = 11;
    const KERTAS_A4               = 20;
    const KERTAS_A4_LANDSCAPE     = 21;
    const KERTAS_FOLIO            = 30;
    const KERTAS_FOLIO_LANDSCAPE  = 31;
    /* ===================== */
    const KERTAS_LETTER_NAMA           = 'Letter';
    const KERTAS_A4_NAMA               = 'A4';
    const KERTAS_LETTER_LANDSCAPE_NAMA = 'Letter-L';
    const KERTAS_A4_LANDSCAPE_NAMA     = 'A4-L';
    const KERTAS_FOLIO_NAMA            = 'Folio';
    const KERTAS_FOLIO_LANDSCAPE_NAMA  = 'Folio-L';

    public $layoutId;
    public $kertasId;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['layoutId, kertasId', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['layoutId, kertasId', 'numerical', 'integerOnly' => true],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'layoutId' => 'Layout Label',
            'kertasId' => 'Kertas',
        ];
    }

    public static function listLayout()
    {
        return [
            self::LAYOUT_DEFAULT_3             => 'Default, Tinggi 3cm',
            self::LAYOUT_4X3                   => '4cm x 3cm',
            self::LAYOUT_MULTI_HARGA           => 'Multi Harga @ (Tinggi 3cm)',
            self::LAYOUT_MULTI_HARGA_TOTAL     => 'Multi Harga Total (Tinggi 3cm)',
            self::LAYOUT_MULTI_HARGA_TOTAL_BIG => 'Multi Harga Total (9,5cm x 6,5cm)',
            self::LAYOUT_AHAD_WARNA            => 'Ahadmart Warna (Tinggi 3cm)',
        ];
    }

    public static function listView()
    {
        return [
            self::LAYOUT_DEFAULT_3             => '_label_rak_default_3_pdf',
            self::LAYOUT_4X3                   => '_label_rak_default_3_lebar_4_pdf',
            self::LAYOUT_MULTI_HARGA           => '_label_rak_multi_harga_pdf',
            self::LAYOUT_MULTI_HARGA_TOTAL     => '_label_rak_multi_harga_total_pdf',
            self::LAYOUT_MULTI_HARGA_TOTAL_BIG => '_label_rak_multi_harga_total_big_pdf',
            self::LAYOUT_AHAD_WARNA            => '_label_rak_ahad_warna_pdf',
        ];
    }

    public static function listNamaKertas()
    {
        return [
            self::KERTAS_A4               => self::KERTAS_A4_NAMA,
            self::KERTAS_A4_LANDSCAPE     => self::KERTAS_A4_LANDSCAPE_NAMA,
            self::KERTAS_LETTER           => self::KERTAS_LETTER_NAMA,
            self::KERTAS_LETTER_LANDSCAPE => self::KERTAS_LETTER_LANDSCAPE_NAMA,
            self::KERTAS_FOLIO            => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_FOLIO_LANDSCAPE  => self::KERTAS_FOLIO_LANDSCAPE_NAMA,
        ];
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4               => 'A4',
            self::KERTAS_A4_LANDSCAPE     => 'A4 Landscape',
            self::KERTAS_LETTER           => 'Letter',
            self::KERTAS_LETTER_LANDSCAPE => 'Letter Landscape',
            self::KERTAS_FOLIO            => 'Folio',
            self::KERTAS_FOLIO_LANDSCAPE  => 'Folio Landscape',
        ];
    }
}
