<?php

/**
 * ReportPoinMemberForm class.
 * ReportPoinMemberForm is the data structure for keeping
 * report poin member form data. It is used by the 'poin member' action of 'ReportController'.
 */
class ReportPoinMemberForm extends CFormModel
{

    public $tahun;
    public $periodeId;
    public $jumlahDari;
    public $jumlahSampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('tahun, periodeId', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('tahun, periodeId, jumlahDari, jumlahSampai', 'numerical', 'integerOnly' => true),
            array('jumlahDari, jumlahSampai', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'periodeId' => 'Periode',
            'jumlahDari' => 'Jumlah Dari',
            'jumlahSampai' => 'Sampai'
        );
    }

}
