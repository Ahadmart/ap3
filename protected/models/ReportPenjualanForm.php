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
        $command->select('pj.id, pj.tanggal,pj.nomor, sum(pd.harga_jual * pd.qty) total,sum(pd.harga_jual * pd.qty)-sum(hpp.harga_beli * pd.qty) margin');
        $command->from(PenjualanDetail::model()->tableName() . ' pd');
        $command->join(Penjualan::model()->tableName() . ' pj', 'pd.penjualan_id=pj.id');
        $command->join(HargaPokokPenjualan::model()->tableName() . ' hpp', 'pd.id=hpp.penjualan_detail_id');
        $command->where("date_format(pj.tanggal,'%Y-%m-%d') between :dari and :sampai", array(
            ':dari' => $dari,
            ':sampai' => $sampai));
        $command->group('pj.id');
        $command->order('pj.tanggal');

        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('sum(pd.harga_jual * pd.qty) total,sum(pd.harga_jual * pd.qty)-sum(hpp.harga_beli * pd.qty) margin');
        $commandRekap->from(PenjualanDetail::model()->tableName() . ' pd');
        $commandRekap->join(Penjualan::model()->tableName() . ' pj', 'pd.penjualan_id=pj.id');
        $commandRekap->join(HargaPokokPenjualan::model()->tableName() . ' hpp', 'pd.id=hpp.penjualan_detail_id');
        $commandRekap->where("date_format(pj.tanggal,'%Y-%m-%d') between :dari and :sampai", array(
            ':dari' => $dari,
            ':sampai' => $sampai));

        if (!empty($this->profilId)) {
            $command->andWhere("pj.profil_id=:profilId", array(
                ':profilId' => $this->profilId
            ));
            $commandRekap->andWhere("pj.profil_id=:profilId", array(
                ':profilId' => $this->profilId
            ));
        }

        if (!empty($this->userId)) {
            $command->andWhere("pj.updated_by=:userId", array(
                ':userId' => $this->userId
            ));
            $commandRekap->andWhere("pj.updated_by=:userId", array(
                ':userId' => $this->userId
            ));
        }

        $penjualan = $command->queryAll();
        $rekap = $commandRekap->queryRow();
        return array(
            'detail' => $penjualan,
            'rekap' => $rekap
        );
    }

}
