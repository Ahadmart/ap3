<?php

/**
 * ReportReturPenjualanForm class.
 * ReportReturPenjualanForm is the data structure for keeping
 * report returPenjualan form data. It is used by the 'returPenjualan' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportReturPenjualanForm extends CFormModel
{
    public $profilId;
    public $userId;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil',
            'userId'   => 'User',
            'dari'     => 'Dari',
            'sampai'   => 'Sampai',
        ];
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaUser()
    {
        $user = User::model()->findByPk($this->userId);
        return $user->nama;
    }

    public function reportReturPenjualan()
    {
        $dari   = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');

        $wProfilId = '';
        if (!empty($this->profilId)) {
            $wProfilId .= ' AND profil.id = :profilId';
        }

        $wUserId = '';
        if (!empty($this->userId)) {
            $wUserId .= ' AND user.id = :userId';
        }

        $sql = "
            SELECT
                SUM(detail.qty * detail.harga_jual) total,
                retur.id,
                retur.nomor,
                retur.tanggal,
                retur.status,
                profil.nama nama_profil,
                `user`.nama nama_user
            FROM
                retur_penjualan_detail detail
                    JOIN
                retur_penjualan retur ON retur.id = detail.retur_penjualan_id
                    AND retur.`status` != :statusDraft
                    AND DATE_FORMAT(retur.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                    JOIN
                profil ON profil.id = retur.profil_id {$wProfilId}
                    JOIN
                `user` ON `user`.id = retur.updated_by {$wUserId}
            GROUP BY retur.id
            ORDER BY retur.nomor
        ";

        $sqlRekap = "
            SELECT
                SUM(detail.qty * detail.harga_jual) total
            FROM
                retur_penjualan_detail detail
                    JOIN
                retur_penjualan retur ON retur.id = detail.retur_penjualan_id
                    AND retur.`status` != :statusDraft
                    AND retur.tanggal BETWEEN :dari AND :sampai
                    JOIN
                profil ON profil.id = retur.profil_id {$wProfilId}
                    JOIN
                `user` ON `user`.id = retur.updated_by {$wUserId}
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', ReturPenjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }

        $comRekap = Yii::app()->db->createCommand($sqlRekap);

        $comRekap->bindValue(':statusDraft', ReturPenjualan::STATUS_DRAFT);
        $comRekap->bindValue(':dari', $dari);
        $comRekap->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $comRekap->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $comRekap->bindValue(':userId', $this->userId);
        }

        $detail = $command->queryAll();
        $rekap  = $comRekap->queryRow();
        return [
            'detail' => $detail,
            'rekap'  => $rekap,
        ];
    }
}
