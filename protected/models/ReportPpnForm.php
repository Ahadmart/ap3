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
        return [
            'totalPpnPembelianPending' => $this->totalPpnPembelianPending()
        ];
    }

    public function totalPpnPembelianPending()
    {
        // Fix me: Masih kurang kondisi periode
        $sql = '
        SELECT 
            SUM(total_ppn_hitung) total
        FROM
            pembelian_ppn
        WHERE
            status = :statusPending
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusPending' => PembelianPpn::STATUS_PENDING
        ]);

        $r = $command->queryRow();
        return $r['total'];
    }
}
