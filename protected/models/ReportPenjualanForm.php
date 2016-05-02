<?php

/**
 * ReportPenjualanForm class.
 * ReportPenjualanForm is the data structure for keeping
 * report penjualan form data. It is used by the 'penjualan' action of 'ReportController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportPenjualanForm extends CFormModel
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
        return array(
            array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('profilId, userId', 'safe')
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'profilId' => 'Profil',
            'userId' => 'User',
            'dari' => 'Dari',
            'sampai' => 'Sampai'
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'profil' => array(self::BELONGS_TO, 'Profil', 'profilId'),
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
        );
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

    public function reportPenjualan()
    {
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');

        $command = Yii::app()->db->createCommand();
        $command->select('*, (t_penjualan.total - t_modal.totalModal) margin');
        $command->from("(SELECT 
                            pd.penjualan_id,
                                pj.nomor,
                                pj.tanggal,
                                pj.profil_id,
                                pj.updated_by,
                                SUM(pd.harga_jual * pd.qty) total
                        FROM
                            penjualan_detail pd
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                        GROUP BY pd.penjualan_id) t_penjualan");
        $command->join("(SELECT 
                            pj.id, SUM(hpp.qty * hpp.harga_beli) totalmodal
                        FROM
                            harga_pokok_penjualan hpp
                        JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                        GROUP BY pj.id) t_modal", "t_penjualan.penjualan_id = t_modal.id");
        $command->order("t_penjualan.nomor");
        $command->where("t_penjualan.profil_id is not null");

        $whereSub = ''; // Variabel untuk menambah kondisi pj, untuk rekap
        if (!empty($this->profilId)) {
            $command->andWhere("t_penjualan.profil_id=:profilId");
            $command->bindValue(":profilId", $this->profilId);
            $whereSub.=" AND pj.profil_id = :profilId";
        }

        if (!empty($this->userId)) {
            $command->andWhere("t_penjualan.updated_by=:userId");
            $command->bindValue(":userId", $this->userId);
            $whereSub.=" AND pj.updated_by = :userId";
        }
        $command->bindValue(":statusDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":dari", $dari);
        $command->bindValue(":sampai", $sampai);

        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('*, (t_penjualan.total - t_modal.totalModal) margin');
        $commandRekap->from("(SELECT SUM(pd.harga_jual * pd.qty) total
                        FROM
                            penjualan_detail pd
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai 
                            {$whereSub}
                        ) t_penjualan, 
                        (SELECT SUM(hpp.qty * hpp.harga_beli) totalmodal
                        FROM
                            harga_pokok_penjualan hpp
                        JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
                            {$whereSub}
                        ) t_modal");
        $commandRekap->where("1=1");
        if (!empty($this->profilId)) {
            $commandRekap->bindValue(":profilId", $this->profilId);
        }
        if (!empty($this->userId)) {
            $commandRekap->bindValue(":userId", $this->userId);
        }
        $commandRekap->bindValue(":statusDraft", Penjualan::STATUS_DRAFT);
        $commandRekap->bindValue(":dari", $dari);
        $commandRekap->bindValue(":sampai", $sampai);

        $penjualan = $command->queryAll();
        $rekap = $commandRekap->queryRow();
        return array(
            'detail' => $penjualan,
            'rekap' => $rekap
        );
    }

}
