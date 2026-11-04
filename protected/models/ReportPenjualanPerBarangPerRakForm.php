<?php

/**
 * ReportPenjualanPerBarangPerRakForm class.
 * ReportPenjualanPerBarangPerRakForm is the data structure for keeping
 * report penjualan per barang per rak form data. It is used by the 'penjualanperbarangperrak' action of 'ReportController'.
 */
class ReportPenjualanPerBarangPerRakForm extends CFormModel
{
    public $profilId;
    public $userId;
    public $dari;
    public $sampai;
    public $rakId;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, userId, rakId', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil (Customer)',
            'userId' => 'User',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'rakId' => 'Rak',
        ];
    }

    public function report($hideOpenTxn = false)
    {
        $dari = DateTime::createFromFormat('d-m-Y', $this->dari);
        $sampai = DateTime::createFromFormat('d-m-Y', $this->sampai);
        $sampai->modify('+1 day');

        $tanggalAwal = $dari->format('Y-m-d') . ' 00:00:00';
        $tanggalAkhir = $sampai->format('Y-m-d') . ' 00:00:00';
        // Yii::log("Awal: ". $tanggalAwal);
        // Yii::log("Akhir: ". $tanggalAkhir);

        $rakQuery = '';
        if (!empty($this->rakId)) {
            $rakQuery = ' AND r.id = :rakId';
        }
        $rakJoin = '';
        if (empty($this->rakId)) {
            $rakJoin = ' LEFT JOIN barang_rak r ON r.id = b.rak_id';
        } else {
            $rakJoin = " JOIN barang_rak r ON r.id = b.rak_id {$rakQuery}";
        }

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND p.profil_id = :profilId';
        }
        if (!empty($this->userId)) {
            $whereSub .= ' AND p.updated_by = :userId';
        }

        $hideOpenTxnJoin = '';
        if ($hideOpenTxn) {
            $hideOpenTxnJoin = ' LEFT JOIN
            kasir ON kasir.user_id = p.updated_by
            AND kasir.waktu_tutup IS NULL ';
        }
        $hideOpenTxnCond = '';
        if ($hideOpenTxn) {
            $hideOpenTxnCond = ' WHERE (kasir.id IS NULL
        OR (kasir.id IS NOT NULL
        AND p.tanggal < kasir.waktu_buka)) ';
        }

        $userId = Yii::app()->user->id;
        $sqlSelect = "
        SELECT
            r.nama AS rak,
            b.barcode,
            b.nama AS nama,
            s.nama AS satuan,
            t_penjualan.qty_jual AS 'qty_penjualan',
            t_penjualan.total_jual AS penjualan,
            total_beli AS pembelian,
            (t_penjualan.total_jual - total_beli) AS margin
        FROM
            (SELECT
                barang_id,
                    SUM(qty) qty_jual,
                    SUM(qty * harga_jual) total_jual
            FROM
                penjualan_detail d
            JOIN penjualan p ON p.id = d.penjualan_id
                AND p.tanggal >= :tanggalAwal
                AND p.tanggal < :tanggalAkhir
                AND p.`status` != :penjualanDraft
                {$whereSub}
                {$hideOpenTxnJoin}
                {$hideOpenTxnCond}
            GROUP BY barang_id) AS t_penjualan
                LEFT JOIN
            (SELECT
                d.barang_id,
                    SUM(hpp.qty) qty_jual,
                    SUM(hpp.qty * hpp.harga_beli) total_beli
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail d ON d.id = hpp.penjualan_detail_id
            JOIN penjualan p ON p.id = d.penjualan_id
                AND p.tanggal >= :tanggalAwal
                AND p.tanggal < :tanggalAkhir
                AND p.`status` != :penjualanDraft
                {$whereSub}
                {$hideOpenTxnJoin}
                {$hideOpenTxnCond}
            GROUP BY barang_id) AS t_hpp ON t_hpp.barang_id = t_penjualan.barang_id
                JOIN
            barang b ON b.id = t_penjualan.barang_id
                {$rakJoin}
                JOIN
            barang_satuan s ON s.id = b.satuan_id
        ORDER BY  t_penjualan.qty_jual DESC , b.nama
                ";

        $sqlRekap = "
        SELECT
            t_penjualan.qty_jual,
            t_penjualan.total_jual,
            t_hpp.qty_hpp,
            t_hpp.total_beli,
            total_jual - total_beli margin,
            (total_jual - total_beli) / total_jual * 100 profit_margin
        FROM
            (SELECT
                SUM(qty) qty_jual, SUM(qty * harga_jual) total_jual
            FROM
                penjualan_detail d
            JOIN penjualan p ON p.id = d.penjualan_id
                AND p.tanggal >= :tanggalAwal
                AND p.tanggal < :tanggalAkhir
                AND p.`status` != :penjualanDraft
            JOIN
                barang b ON b.id = d.barang_id
                {$rakJoin}
                {$whereSub}
                {$hideOpenTxnJoin}
                {$hideOpenTxnCond}
                ) t_penjualan
                CROSS JOIN
            (SELECT
                SUM(hpp.qty) qty_hpp,
                    SUM(hpp.qty * hpp.harga_beli) total_beli
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail d ON d.id = hpp.penjualan_detail_id
            JOIN penjualan p ON p.id = d.penjualan_id
                AND p.tanggal >= :tanggalAwal
                AND p.tanggal < :tanggalAkhir
                AND p.`status` != :penjualanDraft
            JOIN
                barang b ON b.id = d.barang_id
                {$rakJoin}
                {$whereSub}
                {$hideOpenTxnJoin}
                {$hideOpenTxnCond}
                ) t_hpp
        ";

        $command = Yii::app()->db->createCommand($sqlSelect);
        $RCommand = Yii::app()->db->createCommand($sqlRekap);

        $command->bindValue(':penjualanDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':tanggalAwal', $tanggalAwal);
        $command->bindValue(':tanggalAkhir', $tanggalAkhir);

        $RCommand->bindValue(':penjualanDraft', Penjualan::STATUS_DRAFT);
        $RCommand->bindValue(':tanggalAwal', $tanggalAwal);
        $RCommand->bindValue(':tanggalAkhir', $tanggalAkhir);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
            $RCommand->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
            $RCommand->bindValue(':userId', $this->userId);
        }
        if (!empty($this->rakId)) {
            $command->bindValue(':rakId', $this->rakId);
            $RCommand->bindValue(':rakId', $this->rakId);
        }

        $penjualan = $command->queryAll();
        $rekap = $RCommand->queryRow();
        return [
            'detail' => $penjualan,
            'rekap' => $rekap,
        ];
    }

    public function filterRak()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    private function _sesuaikanreport($reportDetail)
    {
        $r = [];
        foreach ($reportDetail as $detail) {
            $r[] = [
                'rak' => $detail['rak'],
                'barcode' => $detail['barcode'],
                'nama' => $detail['nama'],
                'qty_terjual' => $detail['qty_penjualan'],
                'penjualan' => $detail['penjualan'],
                'margin' => $detail['margin'],
                'profit_margin' => $detail['margin'] / $detail['penjualan'],
            ];
        }
        return $r;
    }

    public function toCsv($hideOpenTxn = false)
    {
        return $this->array2csv($this->_sesuaikanreport($this->report($hideOpenTxn)['detail']));
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
}
