<?php

/**
 * ReportDiskonForm class.
 * ReportDiskonForm is the data structure for keeping
 * report diskon form data. It is used by the 'diskon' action of 'ReportController'.
 *
 */
class ReportDiskonForm extends CFormModel
{

    public $profilId;
    public $userId;
    public $tipeDiskonId;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId, tipeDiskonId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil',
            'userId' => 'User',
            'tipeDiskonId' => 'Tipe Diskon',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
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

    public function listTipeDiskon()
    {
        return DiskonBarang::model()->listTipe();
    }

    public function reportDiskon()
    {
        $dari = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i');

        $tipeDiskonCond = '';
        if (isset($this->tipeDiskonId)) {
            $tipeDiskonCond = "WHERE dis.tipe_diskon_id = :tipeDiskonId";
        }

        $penjualanCond = '';
        if (!empty($this->profilId)) {
            $penjualanCond .= " AND p.profil_id = :profilId";
        }

        if (!empty($this->userId)) {
            $penjualanCond .= " AND p.updated_by = :userId";
        }

        $sql = "
            SELECT 
                dis.penjualan_id,
                p.nomor,
                barang.barcode,
                barang.nama,
                dis.harga_normal,
                detail.harga_jual,
                detail.qty,
                (detail.qty * detail.harga_jual) total,
                (SELECT 
                        SUM(qty * harga_beli)
                    FROM
                        harga_pokok_penjualan
                    WHERE
                        penjualan_detail_id = detail.id) hpp,
                dis.tipe_diskon_id
            FROM
                penjualan_diskon dis
                    JOIN
                penjualan_detail detail ON detail.id = dis.penjualan_detail_id
                    JOIN
                penjualan p ON p.id = dis.penjualan_id
                    AND p.status != :penjualanDraft
                    AND DATE_FORMAT(p.tanggal, '%Y-%m-%d %H:%i') BETWEEN :dari AND :sampai
                    {$penjualanCond}
                    JOIN
                barang ON barang.id = detail.barang_id
            {$tipeDiskonCond}
            ORDER BY p.nomor , barang.nama
            ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(":penjualanDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":dari", $dari);
        $command->bindValue(":sampai", $sampai);

        if (isset($this->tipeDiskonId)) {
            $command->bindValue(':tipeDiskonId', $this->tipeDiskonId);
        }

        if (!empty($this->profilId)) {
            $command->bindValue(":profilId", $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(":userId", $this->userId);
        }

        return [
            'detail' => $command->queryAll()
        ];
    }

}
