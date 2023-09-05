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
            'totalPpnPembelianPending'  => $this->totalPpnPembelianPending(),
            'totalPpnPembelianValid'    => $this->totalPpnPembelianValid(),
            'detailPpnPembelianPending' => $this->detailPpnPembelianPending(),
            'detailPpnPembelianValid'   => $this->detailPpnPembelianValid(),
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
            ':statusPending' => PembelianPpn::STATUS_PENDING,
        ]);

        $r = $command->queryRow();
        return $r['total'];
    }

    public function totalPpnPembelianValid()
    {
        // Fix me: Masih kurang kondisi periode
        $sql = '
        SELECT
            SUM(total_ppn_faktur) total
        FROM
            pembelian_ppn
        WHERE
            status = :statusValid
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusValid' => PembelianPpn::STATUS_VALID,
        ]);

        $r = $command->queryRow();
        return $r['total'];
    }

    public function detailPpnPembelianPending()
    {
        $sql = '
        SELECT
            profil.nama,
            no_faktur_pajak,
            pembelian.nomor,
            total_ppn_hitung AS jumlah
        FROM
            pembelian_ppn t
                JOIN
            pembelian ON pembelian.id = t.pembelian_id
                JOIN
            profil ON profil.id = pembelian.profil_id
        WHERE
            t.status = :statusPending
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusPending' => PembelianPpn::STATUS_PENDING,
        ]);

        return $command->queryAll();
    }

    public function detailPpnPembelianValid()
    {
        $sql = '
        SELECT
            profil.nama,
            no_faktur_pajak,
            pembelian.nomor,
            total_ppn_faktur AS jumlah
        FROM
            pembelian_ppn t
                JOIN
            pembelian ON pembelian.id = t.pembelian_id
                JOIN
            profil ON profil.id = pembelian.profil_id
        WHERE
            t.status = :statusValid
        ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':statusValid' => PembelianPpn::STATUS_VALID,
        ]);

        return $command->queryAll();
    }
}
