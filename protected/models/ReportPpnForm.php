<?php

/**
 * ReportPpnForm
 * is the data structure for keeping report ppn data.
 * It used by the 'ppn' action of 'ReportController'.
 */
class ReportPpnForm extends CFormModel
{
    public $periode; // date Ym
    public $detailPpnPembelianValid   = true;
    public $detailPpnPembelianPending = true;

    /**
     * Declares the validation rules.     *
     */
    public function rules()
    {
        return [
            ['periode', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['detailPpnPembelianValid, detailPpnPembelianPending', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'periode'                   => 'Periode',
            'detailPpnPembelianValid'   => 'Detail Ppn Pembelian Valid',
            'detailPpnPembelianPending' => 'Detail Ppn Pembelian Pending',
        ];
    }

    public function reportPpn()
    {
    }
}
