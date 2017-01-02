<?php

/**
 * ReportRekapPenjualanForm class.
 * ReportRekapPenjualanForm is the data structure for keeping
 * report Rekap Penjualan form data. It is used by the 'rekapPenjualan' action of 'ReportController'.
 * 
 * The followings are the available model relations:
 */
class ReportRekapPenjualanForm extends CFormModel
{

    public $profilId;
    public $userId;
    public $dariBulan;
    public $sampaiBulan;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('dariBulan, sampaiBulan', 'required', 'message' => '{attribute} tidak boleh kosong'),
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

    public function tableName()
    {
        return 'report_rekap_penjualan';
    }

    public function reportRekapPenjualan()
    {
        $dari = date_format(date_create_from_format('m/Y', $this->dari), 'Y-m');
        $sampai = date_format(date_create_from_format('m/Y', $this->sampai), 'Y-m');

        $tableName = $this->tableName();

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .=" AND pj.profil_id = :profilId";
        }

        if (!empty($this->userId)) {
            $whereSub.=" AND pj.updated_by = :userId";
        }

        $userId = Yii::app()->user->id;
        $sqlSelect = "
        SELECT 
            t_penjualan.bulan,
            t_penjualan.total,
            t_hpp.total_hpp,
            (t_penjualan.total - t_hpp.total_hpp) margin,
            {$userId} user_id
        FROM
            (SELECT 
                DATE_FORMAT(pj.tanggal, '%Y/%m') bulan,
                    SUM(qty * harga_jual) total
            FROM
                penjualan_detail detail
            JOIN penjualan pj ON pj.id = detail.penjualan_id
                AND DATE_FORMAT(pj.tanggal, '%Y-%m') BETWEEN :dari AND :sampai
                AND pj.status > :statusDraft 
                {$whereSub}
            GROUP BY DATE_FORMAT(pj.tanggal, '%Y/%m')) AS t_penjualan
                JOIN
            (SELECT 
                DATE_FORMAT(pj.tanggal, '%Y/%m') bulan,
                    SUM(hpp.qty * hpp.harga_beli) total_hpp
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pjd ON hpp.penjualan_detail_id = pjd.id
            JOIN penjualan pj ON pj.id = pjd.penjualan_id
                AND DATE_FORMAT(pj.tanggal, '%Y-%m') BETWEEN :dari AND :sampai
                AND pj.status > :statusDraft
                {$whereSub}
            GROUP BY DATE_FORMAT(pj.tanggal, '%Y/%m')) AS t_hpp ON t_hpp.bulan = t_penjualan.bulan
        ORDER BY t_penjualan.bulan
                ";

        $sql = " 
            INSERT INTO 
            {$tableName} 
            {$sqlSelect}
                ";


        Yii::app()->db->createCommand("DELETE FROM {$tableName} WHERE user_id={$userId}")->execute();
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(":statusDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":dari", $dari);
        $command->bindValue(":sampai", $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(":profilId", $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(":userId", $this->userId);
        }

        $command->execute();

        $com = Yii::app()->db->createCommand()
                        ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);

        /*
        $commandRekap = Yii::app()->db->createCommand()
                        ->select('sum(total) total, sum(total_modal) totalmodal, sum(margin) margin')
                        ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);
        */
        
        $penjualan = $com->queryAll();
        //$rekap = $commandRekap->queryRow();
        return array(
            'detail' => $penjualan,
            //'rekap' => $rekap
        );
    }

    public function toCsv()
    {
        $csv = '"tanggal","nomor","nama_profil","total","margin","profit_margin"' . PHP_EOL;

        $penjualan = Yii::app()->db->createCommand()
                ->from($this->tableName())->where('user_id=:userId', [
                    ':userId' => Yii::app()->user->id
                ])
                ->queryAll();

        foreach ($penjualan as $baris) {
            $profitMargin = $baris['margin'] / $baris['total'];
            $csv .= "\"{$baris['tanggal']}\","
                    . "\"{$baris['nomor']}\","
                    . "\"{$baris['nama']}\","
                    . "\"{$baris['total']}\","
                    . "\"{$baris['margin']}\","
                    . "{$profitMargin}"
                    . PHP_EOL;
        }

        return $csv;
    }

}
