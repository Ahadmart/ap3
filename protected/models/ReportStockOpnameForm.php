<?php

/**
 * ReportStockOpnameForm class.
 * ReportStockOpnameForm is the data structure for keeping
 * report Stock Opname form data. It is used by the 'stockopname' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportStockOpnameForm extends CFormModel
{

    const KERTAS_LETTER                = 10;
    const KERTAS_LETTER_LANDSCAPE      = 11;
    const KERTAS_A4                    = 20;
    const KERTAS_A4_LANDSCAPE          = 21;
    const KERTAS_FOLIO                 = 30;
    const KERTAS_FOLIO_LANDSCAPE       = 31;
    /* ===================== */
    const KERTAS_LETTER_NAMA           = 'Letter';
    const KERTAS_A4_NAMA               = 'A4';
    const KERTAS_LETTER_LANDSCAPE_NAMA = 'Letter-L';
    const KERTAS_A4_LANDSCAPE_NAMA     = 'A4-L';
    const KERTAS_FOLIO_NAMA            = 'Folio';
    const KERTAS_FOLIO_LANDSCAPE_NAMA  = 'Folio-L';

    public $userId;
    public $kertas;
    public $dari;
    public $sampai;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['userId, kertas, dari, sampai', 'safe']
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User',
            'dari'   => 'Dari',
            'sampai' => 'Sampai'
        ];
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4               => self::KERTAS_A4_NAMA,
            self::KERTAS_A4_LANDSCAPE     => self::KERTAS_A4_LANDSCAPE_NAMA,
            self::KERTAS_LETTER           => self::KERTAS_LETTER_NAMA,
            self::KERTAS_LETTER_LANDSCAPE => self::KERTAS_LETTER_LANDSCAPE_NAMA,
            self::KERTAS_FOLIO            => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_FOLIO_LANDSCAPE  => self::KERTAS_FOLIO_LANDSCAPE_NAMA
        ];
    }

    public function getNamaUser()
    {
        $user = User::model()->findByPk($this->userId);
        return $user->nama;
    }

    public function report()
    {
        $dari   = DateTime::createFromFormat('d-m-Y', $this->dari);
        $sampai = DateTime::createFromFormat('d-m-Y', $this->sampai);
        $sampai->modify('+1 day');
        return ['detail' => $this->laporanPerItemPerNota($dari->format('Y-m-d') . ' 00:00:00', $sampai->format('Y-m-d') . ' 00:00:00')];
    }

    public function laporanPerItemPerNota($dari, $sampai)
    {
        $userCondition = '';
        if (!empty($this->userId)) {
            $userCondition .= "
        WHERE
            d.updated_by = :userId
            ";
        }

        $sql = "
        SELECT 
            s.nomor,
            s.tanggal,
            s.keterangan,
            d.barang_id,
            b.barcode,
            b.nama,
            d.qty_tercatat,
            d.qty_sebenarnya,
            (SELECT 
                    harga_beli
                FROM
                    inventory_balance
                WHERE
                    created_at <= s.tanggal
                        AND barang_id = d.barang_id
                ORDER BY id DESC
                LIMIT 1) harga_beli,
            (SELECT 
                    harga
                FROM
                    barang_harga_jual
                WHERE
                    created_at <= s.tanggal
                        AND barang_id = d.barang_id
                ORDER BY id DESC
                LIMIT 1) harga_jual,
            `user`.nama nama_user
        FROM
            stock_opname_detail d
                JOIN
            stock_opname s ON s.id = d.stock_opname_id
                AND s.tanggal >= :dari
                AND s.tanggal < :sampai
                AND s.`status` = :statusSO
                JOIN
            barang b ON b.id = d.barang_id
                JOIN
            `user` ON `user`.id = d.updated_by
        {$userCondition}
        ORDER BY s.nomor , b.nama
            ";

        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues([
            ':dari'     => $dari,
            ':sampai'   => $sampai,
            ':statusSO' => StockOpname::STATUS_SO,
        ]);
        if (!empty($userCondition)) {
            $command->bindValue(':userId', $this->userId);
        }

        return $command->queryAll();
    }

    /**
     * 
     * @return boolean true jika perhitungan menggunakan harga jual
     */
    public static function isHitungDenganHargaJual()
    {
        $sql     = "
        SELECT 
            nilai
        FROM
            config
        WHERE
            nama = 'laporanso.dengan_hargajual'            
            ";
        $command = Yii::app()->db->createCommand($sql);
        $r       = $command->queryRow();

        return $r['nilai'] == '1';
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

    public function reportKeCsv($report)
    {
        return $this->array2csv($report);
    }
}
