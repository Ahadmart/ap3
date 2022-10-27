<?php

/**
 * ReportMutasiPoinForm class.
 * ReportMutasiPoinForm is the data structure for keeping
 * report Mutasi Koin form data. It is used by the 'mutasikoin' action of 'ReportController'.
 */
class ReportMutasiPoinForm extends CFormModel
{
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
}
