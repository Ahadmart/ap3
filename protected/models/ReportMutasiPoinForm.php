<?php

/**
 * ReportMutasiPoinForm class.
 * ReportMutasiPoinForm is the data structure for keeping
 * report Top rank form data. It is used by the 'toprank' action of 'ReportController'.
 *
 * The followings are the available model relations:
 */
class ReportMutasiPoinForm extends CFormModel
{
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $dari;
    public $sampai;
    public $nomor;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['nomor', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['dari, sampai', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'nomor'  => 'Nomor Member / No Telp',
            'dari'   => 'Dari',
            'sampai' => 'Sampai',
        ];
    }

    public function reportMutasiPoin()
    {
        $clientAPI = new AhadMembershipClient();
        $dari      = !empty($this->dari) ? date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d') : '';
        $sampai    = !empty($this->sampai) ? date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d') : '';
        return $clientAPI->mutasiPoin([
            'nomor'  => $this->nomor,
            'dari'   => $dari,
            'sampai' => $sampai,
        ]);
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen('php://output', 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    public function toCsv()
    {
        // $report = $this->reportMutasiPoin();
        // return $this->array2csv($report);
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4     => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO  => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
        ];
    }
}
