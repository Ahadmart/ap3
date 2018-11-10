<?php

/**
 * ReportPenjualanSalesOrderForm class.
 * ReportPenjualanSalesOrderForm is the data structure for keeping
 * report penjualan from sales order form data. It is used by the 'penjualansalesorder' action of 'ReportController'.
 */
class ReportPenjualanSalesOrderForm extends CFormModel
{

    public $profilId;
    public $userId;
    public $dari;
    public $sampai;
    public $kategoriId;
    public $semuaPenjualan = false; // Jika true,  maka semua penjualan ditampilkan, termasuk yang bukan dari SO

    /**
     * Declares the validation rules.
     */

    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId, kategoriId', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId'   => 'Profil',
            'userId'     => 'User',
            'dari'       => 'Dari',
            'sampai'     => 'Sampai',
            'kategoriId' => 'Kategori'
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
        return 'report_penjualan_salesorder';
    }

    public function report()
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i');

        $tableName = $this->tableName();

        $kategoriQuery = '';
        if (!empty($this->kategoriId)) {
            $kategoriQuery = 'JOIN barang ON pd.barang_id = barang.id
                                AND barang.kategori_id = :kategoriId';
        }

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= " AND pj.profil_id = :profilId";
        }

        if (!empty($this->userId)) {
            $whereSub .= " AND pj.updated_by = :userId";
        }

        $where = '';
        if (!$this->semuaPenjualan) {
            $whereSub = ' AND so.penjualan_id IS NOT NULL';
            $where    .= ' AND so.penjualan_id IS NOT NULL';
        }


        $userId    = Yii::app()->user->id;
        $sqlSelect = "
        SELECT
            t_penjualan.penjualan_id,
            t_penjualan.nomor,
            so.id,
            so.nomor,
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
                AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d %H:%i') BETWEEN :dari AND :sampai
            LEFT JOIN so on pj.id = so.penjualan_id
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
                AND DATE_FORMAT(pj.tanggal, '%Y-%m-%d %H:%i') BETWEEN :dari AND :sampai
             LEFT JOIN so on pj.id = so.penjualan_id
               {$whereSub}
            GROUP BY pj.id) t_modal ON t_penjualan.penjualan_id = t_modal.id
                JOIN
            profil ON t_penjualan.profil_id = profil.id
                LEFT JOIN
            so ON t_penjualan.penjualan_id = so.penjualan_id
        WHERE
            t_penjualan.profil_id IS NOT NULL {$where}
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
        $rekap     = $commandRekap->queryRow();
        return [
            'detail' => $penjualan,
            'rekap'  => $rekap
        ];
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function toCsv()
    {
        $penjualan = Yii::app()->db->createCommand()
                ->select(['tanggal', 'nama', 'penjualan_nomor', 'so_nomor', 'total', 'total_modal', 'margin'])
                ->from($this->tableName())->where('user_id=:userId',
                        [
                    ':userId' => Yii::app()->user->id
                ])
                ->queryAll();

        return $this->array2csv($penjualan);
    }

    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen('php://output', 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

}
