<?php

/**
 * ReportRekapDiskonForm class.
 * ReportRekapDiskonForm is the data structure for keeping
 * report rekap diskon form data. It is used by the 'rekapdiskon' action of 'ReportController'.
 *
 */
class ReportRekapDiskonForm extends CFormModel
{
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

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
            ['tipeDiskonId', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'tipeDiskonId' => 'Tipe Diskon',
            'dari'         => 'Dari',
            'sampai'       => 'Sampai',
        ];
    }

    public function listTipeDiskon()
    {
        return DiskonBarang::model()->listTipe();
    }

    public function reportRekapDiskon()
    {
        $dari   = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
        $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');

        $tipeDiskonCond = '';
        if ($this->tipeDiskonId != '') {
            $tipeDiskonCond = "WHERE dis.tipe_diskon_id = :tipeDiskonId";
        }

        $sql = "
        SELECT
            barang.barcode, barang.nama, t_rekap.*
        FROM
            (SELECT
                barang_id,
                    SUM(harga_normal) harga_normal,
                    SUM(total) harga_jual,
                    SUM(qty) qty,
                    SUM(hpp) hpp,
                    SUM(total) - SUM(hpp) margin,
                    MIN(tipe_diskon_id) tipe_diskon_id,
                    COUNT(DISTINCT tipe_diskon_id) banyak_tipe_diskon_id
            FROM
                (SELECT
                    det.barang_id,
                    det.qty,
                    det.harga_jual,
                    (det.qty * dis.harga_normal) harga_normal,
                    dis.tipe_diskon_id,
                    (det.qty * det.harga_jual) total,
                    (SELECT
                            SUM(qty * harga_beli)
                        FROM
                            harga_pokok_penjualan
                        WHERE
                            penjualan_detail_id = det.id) hpp,
                    det.id
            FROM
                penjualan_diskon dis
            JOIN penjualan_detail det ON det.id = dis.penjualan_detail_id
            JOIN penjualan p ON p.id = dis.penjualan_id
                AND p.status != :penjualanDraft
                AND DATE_FORMAT(p.tanggal, '%Y-%m-%d') BETWEEN :dari AND :sampai
            {$tipeDiskonCond}) AS t1
            GROUP BY barang_id) AS t_rekap
                JOIN
            barang ON barang.id = t_rekap.barang_id
        ORDER BY barang.nama
            ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(":penjualanDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":dari", $dari);
        $command->bindValue(":sampai", $sampai);

        if ($this->tipeDiskonId != '') {
            $command->bindValue(':tipeDiskonId', $this->tipeDiskonId);
        }

        return [
            'detail' => $command->queryAll(),
        ];
    }
    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen('php://output', 'w');
        // fputcsv($df, array_merge(array_keys(reset($array)), ['nama_tipe_diskon']));
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }

    /**
     * Export Laporan Diskon ke CSV
     * @return text csv beserta header
     */
    public function toCsv($report)
    {
        $baris = [];
        foreach ($report as $row) {
            // Tambahkan kolom jenis_diskon untuk menampilkan nama tipe diskon di file csv
            $baris[] = array_merge($row, ['jenis_diskon' => DiskonBarang::namaTipeDiskon($row['tipe_diskon_id'])]);
        }
        // Yii::log(print_r($baris, true));
        return $this->array2csv($baris);
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4     => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO  => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
        ];
    }
}
