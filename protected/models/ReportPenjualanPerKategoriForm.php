<?php

/**
 * ReportPenjualanPerKategoriForm class.
 * ReportPenjualanPerKategoriForm is the data structure for keeping
 * report penjualan per kategori form data. It is used by the 'penjualanperkategori' action of 'ReportController'.
 *
 */
class ReportPenjualanPerKategoriForm extends CFormModel
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
            'profilId'   => 'Profil (Customer)',
            'userId'     => 'User',
            'dari'       => 'Dari',
            'sampai'     => 'Sampai',
            'kategoriId' => 'Kategori'
        ];
    }

    public function report($hideOpenTxn = false)
    {
        $dari   = DateTime::createFromFormat('d-m-Y', $this->dari);
        $sampai = DateTime::createFromFormat('d-m-Y', $this->sampai);
        $sampai->modify('+1 day');

        $tanggalAwal  = $dari->format('Y-m-d') . ' 00:00:00';
        $tanggalAkhir = $sampai->format('Y-m-d') . ' 00:00:00';

        $kategoriQuery = '';
        if (!empty($this->kategoriId)) {
            $kategoriQuery = ' AND k.id = :kategoriId';
        }

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .=" AND p.profil_id = :profilId";
        }
        if (!empty($this->userId)) {
            $whereSub.=" AND p.updated_by = :userId";
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

        $userId    = Yii::app()->user->id;
        $sqlSelect = "
        SELECT 
            k.nama AS kategori,
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
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
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
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY barang_id) AS t_hpp ON t_hpp.barang_id = t_penjualan.barang_id
                JOIN
            barang b ON b.id = t_penjualan.barang_id
                JOIN
            barang_kategori k ON k.id = b.kategori_id {$kategoriQuery}
                JOIN
            barang_satuan s ON s.id = b.satuan_id
        ORDER BY k.nama , t_penjualan.total_jual DESC , b.nama
                ";


        $command = Yii::app()->db->createCommand($sqlSelect);

        $command->bindValue(":penjualanDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":tanggalAwal", $tanggalAwal);
        $command->bindValue(":tanggalAkhir", $tanggalAkhir);

        if (!empty($this->profilId)) {
            $command->bindValue(":profilId", $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(":userId", $this->userId);
        }
        if (!empty($this->kategoriId)) {
            $command->bindValue(':kategoriId', $this->kategoriId);
        }

        $penjualan = $command->queryAll();
        //$rekap     = '';//$commandRekap->queryRow();
        return [
            'detail' => $penjualan,
                //'rekap'  => $rekap
        ];
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function toCsv($hideOpenTxn = false)
    {
        return $this->array2csv($this->report($hideOpenTxn)['detail']);
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
