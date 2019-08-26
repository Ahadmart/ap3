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
    public $kategoriId;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('profilId, userId, kategoriId', 'safe')
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
            'sampai' => 'Sampai',
            'kategoriId' => 'Kategori'
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

    public function tableName()
    {
        return 'report_penjualan';
    }

    public function reportPenjualan()
    {
        $dari = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i').":00";
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i').":00";

        $tableName = $this->tableName();

        $kategoriQuery = '';
        if (!empty($this->kategoriId)) {
            $kategoriQuery = 'JOIN barang ON pd.barang_id = barang.id
                                AND barang.kategori_id = :kategoriId';
        }

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
            t_penjualan.penjualan_id,
            t_penjualan.nomor,
            t_penjualan.tanggal,
            t_penjualan.profil_id,
            t_penjualan.updated_by,
            t_penjualan.total,
            t_modal.total_modal,
            profil.nama,
            (t_penjualan.total - t_modal.total_modal) margin,
            {$userId} user_id
        FROM
            (SELECT
                pd.penjualan_id,
                    pj.nomor,
                    pj.tanggal,
                    pj.profil_id,
                    pj.updated_by,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal >= :dari AND pj.tanggal <= :sampai
                {$whereSub}
            {$kategoriQuery}
            GROUP BY pd.penjualan_id) t_penjualan
                JOIN
            (SELECT
                pj.id, SUM(hpp.qty * hpp.harga_beli) total_modal
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            {$kategoriQuery}
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal >= :dari AND pj.tanggal <= :sampai
                {$whereSub}
            GROUP BY pj.id) t_modal ON t_penjualan.penjualan_id = t_modal.id
                JOIN
            profil ON t_penjualan.profil_id = profil.id
        WHERE
            t_penjualan.profil_id IS NOT NULL
        ORDER BY t_penjualan.nomor
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
        if (!empty($this->kategoriId)) {
            $command->bindValue(':kategoriId', $this->kategoriId);
        }

        $command->execute();

        $com = Yii::app()->db->createCommand()
                        ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);

        $commandRekap = Yii::app()->db->createCommand()
                        ->select('sum(total) total, sum(total_modal) totalmodal, sum(margin) margin')
                        ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);

        $penjualan = $com->queryAll();
        $rekap = $commandRekap->queryRow();
        return array(
            'detail' => $penjualan,
            'rekap' => $rekap
        );
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
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
