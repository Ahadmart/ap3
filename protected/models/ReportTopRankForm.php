<?php

/**
 * ReportTopRankForm class.
 * ReportTopRankForm is the data structure for keeping
 * report Top rank form data. It is used by the 'toprank' action of 'ReportController'.
 *
 * The followings are the available model relations:
 */
class ReportTopRankForm extends CFormModel
{

    const SORT_BY_QTY_ASC    = 1;
    const SORT_BY_QTY_DSC    = 2;
    const SORT_BY_OMZET_ASC  = 3;
    const SORT_BY_OMZET_DSC  = 4;
    const SORT_BY_MARGIN_ASC = 5;
    const SORT_BY_MARGIN_DSC = 6;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $dari;
    public $sampai;
    public $profilId;
    public $kategoriId;
    public $rakId;
    public $limit = 200;
    public $sortBy;
    public $kertas;
    public $strukLv1;
    public $strukLv2;
    public $strukLv3;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai, sortBy', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, kategoriId, rakId, limit, kertas, strukLv1, strukLv2, strukLv3', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId'   => 'Profil (Supplier)',
            'kategoriId' => 'Kategori',
            'rakId'      => 'Rak',
            'limit'      => 'Jumlah Item',
            'sortBy'     => 'Urut berdasarkan',
            'dari'       => 'Dari',
            'sampai'     => 'Sampai',
            'strukLv1'   => 'Struktur Level 1',
            'strukLv2'   => 'Struktur Level 2',
            'strukLv3'   => 'Struktur Level 3',
        ];
    }

    public function getNamaProfil()
    {
        $profil = Profil::model()->findByPk($this->profilId);
        return $profil->nama;
    }

    public function getNamaKategori()
    {
        $model = KategoriBarang::model()->findByPk($this->kategoriId);
        return $model->nama;
    }

    public function getNamaRak()
    {
        $model = RakBarang::model()->findByPk($this->rakId);
        return $model->nama;
    }

    public function reportTopRank($hideOpenTxn = false)
    {
        $strukList = [];
        if ($this->strukLv3 > 0) {
            $strukList[] = $this->strukLv3;
        } else if ($this->strukLv2 > 0) {
            $strukList = StrukturBarang::listChildStruk($this->strukLv2);
        } else if ($this->strukLv1 > 0) {
            $strukturListLv2 = StrukturBarang::listChildStruk($this->strukLv1);
            foreach ($strukturListLv2 as $strukturIdLv2) {
                $strukList = array_merge($strukList, StrukturBarang::listChildStruk($strukturIdLv2));
            }
        } else {
            // Tidak ada struktur dipilih
            return $this->reportTopRankLv3('', $hideOpenTxn);
        }
        // var_dump($strukList);
        $strukComma = implode(',', $strukList);
        // var_dump($strukComma);
        // Yii::app()->end();
        return $this->reportTopRankLv3($strukComma, $hideOpenTxn);
    }

    public function reportTopRankLv3($strukComma = '', $hideOpenTxn)
    {
        $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d') . ' 00:00:00';

        $tglAkhir = DateTime::createFromFormat('d-m-Y', $this->sampai);
        $tglAkhir->modify('+1 day');
        $sampai = $tglAkhir->format('Y-m-d') . ' 00:00:00';

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

        $command = Yii::app()->db->createCommand();
        $command->select('t_penjualan.barang_id, barang.barcode, barang.nama, t_penjualan.totalqty, t_penjualan.total, t_modal.totalmodal, (t_penjualan.total - t_modal.totalModal) margin, t_penjualan.totalqty/DATEDIFF(:sampai, :dari) avgday, t_stok.stok');
        $command->from("(SELECT
                                barang_id,
                                SUM(pd.harga_jual * pd.qty) total,
                                SUM(pd.qty) totalqty
                        FROM
                            penjualan_detail pd
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND pj.tanggal >= :dari AND pj.tanggal < :sampai
                        ${hideOpenTxnJoin}
                        ${hideOpenTxnCond}
                        GROUP BY barang_id) t_penjualan");
        $command->join("(SELECT
                            barang_id, SUM(hpp.qty * hpp.harga_beli) totalmodal
                        FROM
                            harga_pokok_penjualan hpp
                        JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND pj.tanggal >= :dari AND pj.tanggal < :sampai
                        ${hideOpenTxnJoin}
                        ${hideOpenTxnCond}
                        GROUP BY barang_id) t_modal", "t_penjualan.barang_id = t_modal.barang_id");
        $command->join('barang', 't_penjualan.barang_id=barang.id');
        $command->join('(SELECT
                            barang_id, SUM(qty) stok
                        FROM
                            inventory_balance
                        GROUP BY barang_id
                        /*HAVING SUM(qty) > 0*/) t_stok', "barang.id = t_stok.barang_id"); // Jumlah Stok tidak relevan, kalau yang diinginkan penjualan toprank / slow moving
        $command->where("barang.id is not null");

        if (!empty($this->rakId) && $this->rakId > 0) {
            $command->andWhere('barang.rak_id=:rakId');
        }

        if (!empty($this->kategori) && $this->kategoriId > 0) {
            $command->andWhere('barang.kategori_id=:kategoriId');
        }

        if (!empty($this->profilId)) {
            $command->join('supplier_barang sb', 'sb.barang_id = t_penjualan.barang_id');
            $command->andWhere('sb.supplier_id=:profilId');
        }

        if (!empty($strukComma)) {
            $command->andWhere('barang.struktur_id IN (' . $strukComma . ')');
        }

        switch ($this->sortBy) {
            case self::SORT_BY_QTY_DSC:
                $command->order('totalqty desc');
                break;
            case self::SORT_BY_QTY_ASC:
                $command->order('totalqty');
                break;
            case self::SORT_BY_OMZET_DSC:
                $command->order('total desc');
                break;
            case self::SORT_BY_OMZET_ASC:
                $command->order('total');
                break;
            case self::SORT_BY_MARGIN_DSC:
                $command->order('(t_penjualan.total - t_modal.totalModal) desc');
                break;
            case self::SORT_BY_MARGIN_ASC:
                $command->order('(t_penjualan.total - t_modal.totalModal)');
                break;
        }

        if ($this->limit != '') {
            $command->limit($this->limit);
        }

        $command->bindValue(":statusDraft", Penjualan::STATUS_DRAFT);
        $command->bindValue(":dari", $dari);
        $command->bindValue(":sampai", $sampai);
        if (!empty($this->rakId) && $this->rakId > 0) {
            $command->bindValue(":rakId", $this->rakId);
        }
        if (!empty($this->kategori) && $this->kategoriId > 0) {
            $command->bindValue(":kategoriId", $this->kategoriId);
        }

        if (!empty($this->profilId)) {
            $command->bindValue(":profilId", $this->profilId);
        }

        // echo $command->text;
        // echo '<br />';

        return $command->queryAll();
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

    public function toCsv($hideOpenTxn = false)
    {
        $report = $this->reportTopRank($hideOpenTxn);
        return $this->array2csv($report);
    }

    public function filterKategori()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterRak()
    {
        return ['' => '[SEMUA]'] + CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function listSortBy()
    {
        return [
            'Top Rank'    => [
                self::SORT_BY_QTY_DSC    => 'Jumlah Barang [z-a]',
                self::SORT_BY_OMZET_DSC  => 'Omset [z-a]',
                self::SORT_BY_MARGIN_DSC => 'Profit [z-a]',
            ],
            'Slow Moving' => [
                self::SORT_BY_QTY_ASC    => 'Jumlah Barang [a-z]',
                self::SORT_BY_OMZET_ASC  => 'Omset [a-z]',
                self::SORT_BY_MARGIN_ASC => 'Profit [a-z]',
            ],
        ];
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
