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
    const JENIS_PENJUALAN = 1;
    const JENIS_TRANSFER  = 2;

    public $profilId;
    public $userId;
    public $dari;
    public $sampai;
    public $kategoriId;
    public $transferMode;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId, kategoriId, transferMode', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId'     => 'Profil',
            'userId'       => 'User',
            'dari'         => 'Dari',
            'sampai'       => 'Sampai',
            'kategoriId'   => 'Kategori',
            'transferMode' => 'Jenis',
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'profil' => [self::BELONGS_TO, 'Profil', 'profilId'],
            'user'   => [self::BELONGS_TO, 'User', 'userId'],
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

    public function tableName()
    {
        return 'report_penjualan';
    }

    public function reportPenjualan($hideOpenTxn = false)
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i') . ':00';
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i') . ':00';

        $tableName = $this->tableName();

        $kategoriQuery = '';
        if (!empty($this->kategoriId)) {
            $kategoriQuery = 'JOIN barang ON pd.barang_id = barang.id
                                AND barang.kategori_id = :kategoriId';
        }

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
        }

        if ($this->transferMode > 0) {
            $whereSub .= ' AND pj.transfer_mode = :transferMode';
        }

        $hideOpenTxnJoin = '';
        if ($hideOpenTxn) {
            $hideOpenTxnJoin = ' LEFT JOIN
            kasir ON kasir.user_id = pj.updated_by
            AND kasir.waktu_tutup IS NULL ';
        }
        $hideOpenTxnCond = '';
        if ($hideOpenTxn) {
            $hideOpenTxnCond = ' WHERE (kasir.id IS NULL
        OR (kasir.id IS NOT NULL
        AND pj.tanggal < kasir.waktu_buka)) ';
        }

        $userId    = Yii::app()->user->id;
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
            user.nama AS nama_user,
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
            {$hideOpenTxnJoin}
            {$hideOpenTxnCond}
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
                {$hideOpenTxnJoin}
                {$hideOpenTxnCond}
            GROUP BY pj.id) t_modal ON t_penjualan.penjualan_id = t_modal.id
                JOIN
            profil ON t_penjualan.profil_id = profil.id
                JOIN
            user ON t_penjualan.updated_by = user.id
        WHERE
            t_penjualan.profil_id IS NOT NULL
        ORDER BY t_penjualan.nomor
                ";

        $sql = "
            INSERT INTO
            {$tableName}
            (penjualan_id, nomor, tanggal, profil_id, updated_by, total, total_modal, nama, margin, nama_user, user_id)
            {$sqlSelect}
                ";

        $jmlItemQuery = "
            SELECT
                COUNT(DISTINCT barang_id) jml_item
            FROM
                penjualan_detail pd
                    JOIN
                penjualan pj ON pd.penjualan_id = pj.id AND pj.status != :statusDraft
                    {$kategoriQuery}
            WHERE
                pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
        ";
        $qtyQuery = "
        SELECT SUM(qty) qty
        FROM penjualan_detail pd
             JOIN penjualan pj ON pd.penjualan_id=pj.id AND pj.status != :statusDraft
             {$kategoriQuery}
        WHERE pj.tanggal BETWEEN :dari AND :sampai
            {$whereSub}
        ";

        Yii::app()->db->createCommand("DELETE FROM {$tableName} WHERE user_id={$userId}")->execute();
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        $jmlItemCom = Yii::app()->db->createCommand($jmlItemQuery);
        $jmlItemCom->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $jmlItemCom->bindValue(':dari', $dari);
        $jmlItemCom->bindValue(':sampai', $sampai);

        $qtyCom = Yii::app()->db->createCommand($qtyQuery);
        $qtyCom->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $qtyCom->bindValue(':dari', $dari);
        $qtyCom->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
            $jmlItemCom->bindValue(':profilId', $this->profilId);
            $qtyCom->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
            $jmlItemCom->bindValue(':userId', $this->userId);
            $qtyCom->bindValue(':userId', $this->userId);
        }
        if (!empty($this->kategoriId)) {
            $command->bindValue(':kategoriId', $this->kategoriId);
            $jmlItemCom->bindValue(':kategoriId', $this->kategoriId);
            $qtyCom->bindValue(':kategoriId', $this->kategoriId);
        }

        if ($this->transferMode > 0) {
            switch ($this->transferMode) {
                case self::JENIS_PENJUALAN:
                    $q = Penjualan::JENIS_PENJUALAN;
                    break;

                case self::JENIS_TRANSFER:
                    $q = Penjualan::JENIS_TRANSFER;
                    break;
            }
            $command->bindValue(':transferMode', $q);
            $jmlItemCom->bindValue(':transferMode', $q);
            $qtyCom->bindValue(':transferMode', $q);
        }

        $command->execute();

        $com = Yii::app()->db->createCommand()
            ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);

        $commandRekap = Yii::app()->db->createCommand()
            ->select('sum(total) total, sum(total_modal) totalmodal, sum(margin) margin')
            ->from($tableName)->where('user_id=:userId', [':userId' => $userId]);

        $penjualan = $com->queryAll();
        $rekap     = $commandRekap->queryRow();
        $jmlItem   = $jmlItemCom->queryRow();
        $qty       = $qtyCom->queryRow();

        return [
            'detail'  => $penjualan,
            'rekap'   => $rekap,
            'jmlItem' => $jmlItem,
            'qty'     => $qty,
        ];
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterTransfer()
    {
        return [
            ''                    => '[SEMUA]',
            self::JENIS_PENJUALAN => 'Penjualan',
            self::JENIS_TRANSFER  => 'Transfer Barang',
        ];
    }

    public function toCsv()
    {
        $csv = '"tanggal","nomor","nama_profil","total","margin","profit_margin","nama_user"' . PHP_EOL;

        $penjualan = Yii::app()->db->createCommand()
            ->from($this->tableName())->where('user_id=:userId', [
                ':userId' => Yii::app()->user->id,
            ])
            ->queryAll();

        foreach ($penjualan as $baris) {
            $profitMargin = $baris['total'] != 0 ? $baris['margin'] / $baris['total'] : 0;
            $csv .= "\"{$baris['tanggal']}\","
                . "\"{$baris['nomor']}\","
                . "\"{$baris['nama']}\","
                . "\"{$baris['total']}\","
                . "\"{$baris['margin']}\","
                . "\"{$profitMargin}\","
                . "\"{$baris['nama_user']}\""
                . PHP_EOL;
        }

        return $csv;
    }
}
