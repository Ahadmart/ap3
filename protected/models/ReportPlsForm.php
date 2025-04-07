<?php

/**
 * ReportPlsForm class.
 * ReportPlsForm is the data structure for keeping
 * report PLS form data. It is used by the 'pls' action of 'ReportController'.
 *
 * The followings are the available model relations:
 */
class ReportPlsForm extends CFormModel
{
    const SORT_BY_SISA_HARI_ASC = 1;
    const SORT_BY_SISA_HARI_DSC = 2;
    /* ============= */
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $jumlahHari;
    public $profilId;
    // public $sisaHariMax; // diganti dengan orderPeriod
    public $orderPeriod;
    /* Parameter untuk PO: */
    public $leadTime; // Jarak antar order, sampai ordernya sampai
    public $ssd; // Safety Stock Day (Stok jaga-jaga)
    public $semuaBarang = false; // Jika true, juga mengambil barang tanpa penjualan
    /* end Parameter untuk PO */
    public $rakId;
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
            ['jumlahHari, sortBy, orderPeriod', 'required', 'message' => '{attribute} tidak boleh kosong'],
            ['profilId, rakId, kertas, leadTime, ssd, semuaBarang', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'jumlahHari'  => 'Range Analisa Penjualan',
            'profilId'    => 'Profil (Opsional)',
            // 'sisaHariMax' => 'Limit Estimasi Sisa Hari <=',
            'orderPeriod' => 'Order Period',
            'leadTime'    => 'Lead Time',
            'ssd'         => 'Safety Stock Day',
            'semuaBarang' => 'Semua barang',
            'rakId'       => 'Rak (Opsional)',
            'sortBy'      => 'Urut berdasarkan',
            'strukLv1'    => 'Struktur Level 1',
            'strukLv2'    => 'Struktur Level 2',
            'strukLv3'    => 'Struktur Level 3',
        ];
    }

    public function getNamaProfil()
    {
        $model = Profil::model()->findByPk($this->profilId);
        return $model->nama;
    }

    public function listChildStruk($id)
    {
        $criteria = new CDbCriteria();
        if (empty($id)) {
            $criteria->condition = 'status=:publish AND parent_id IS NULL';
            $criteria->params    = [
                ':publish' => StrukturBarang::STATUS_PUBLISH,
            ];
        } else {
            $criteria->condition = 'status=:publish AND parent_id=:id';
            $criteria->params    = [
                ':publish' => StrukturBarang::STATUS_PUBLISH,
                ':id'      => $id,
            ];
        }
        $criteria->order = 'nama';

        $childStruk = StrukturBarang::model()->findAll($criteria);

        $r = [];
        foreach ($childStruk as $struk) {
            $r[] = $struk->id;
        }
        return $r;
    }

    public function reportPls()
    {
        $strukturList = [];
        if ($this->strukLv3 > 0) {
            $strukturList[] = $this->strukLv3;
            // echo ',this->strukId: ' . $this->strukLv3;
        } elseif ($this->strukLv2 > 0) {
            $strukturList = $this->listChildStruk($this->strukLv2);
        } elseif ($this->strukLv1 > 0) {
            $strukturListLv2 = $this->listChildStruk($this->strukLv1);
            foreach ($strukturListLv2 as $strukturIdLv2) {
                $strukturList = array_merge($strukturList, $this->listChildStruk($strukturIdLv2));
            }
        } else {
            // Struktur tidak dipilih, return all
            $r['all'] = $this->reportPlsLevel3(null);
            return $r;
        }

        $r = [];
        foreach ($strukturList as $strukId) {
            // echo ', strukId: ' . $strukId;
            $r[$strukId] = $this->reportPlsLevel3($strukId);
        }
        return $r;
    }

    public function reportPlsLevel3($strukId)
    {
        $whereStruk = '';
        if (!empty($strukId)) {
            $whereStruk = '
                JOIN
            barang ON barang.id = penjualan_detail.barang_id
                AND barang.struktur_id = :strukId
        ';
        }

        $command = Yii::app()->db->createCommand();
        $command->select('
            t_jualan.*,
            barang.barcode,
            barang.nama,
            barang.restock_min,
            t_jualan.qty / :range ads,
            t_stok.qty stok,
            t_stok.qty / (t_jualan.qty / :range) sisa_hari
                ');
        $command->from("
            (SELECT
                barang_id, SUM(qty) qty
            FROM
                penjualan_detail
                    JOIN
                penjualan ON penjualan.id = penjualan_detail.penjualan_id
                    AND penjualan.status != :statusDraft
                    {$whereStruk}
            WHERE
                penjualan.created_at BETWEEN DATE_SUB(NOW(), INTERVAL :range DAY) AND NOW()
            GROUP BY barang_id) AS t_jualan
                ");
        $command->join('
            (SELECT
                barang_id, SUM(qty) qty
            FROM
                inventory_balance
            GROUP BY barang_id) AS t_stok
                ', 't_stok.barang_id = t_jualan.barang_id');
        $command->join('barang', 't_jualan.barang_id = barang.id');
        //$command->where("t_stok.qty / (t_jualan.qty / :range) <= :orderPeriod");
        $command->where('(t_jualan.qty / :range) * (:orderPeriod + :leadTime + :ssd) + barang.restock_min > t_stok.qty');
        $command->order('(t_jualan.qty / :range) * (:orderPeriod + :leadTime + :ssd) + barang.restock_min ' . $this->listNamaSortBy()[$this->sortBy]);

        if (!empty($this->profilId)) {
            $command->join('supplier_barang sb', 'sb.barang_id = t_jualan.barang_id');
            $command->andWhere('sb.supplier_id=:profilId');
        }

        if (!empty($this->rakId)) {
            $command->andWhere('barang.rak_id = ' . $this->rakId);
        }

        $command->andWhere('barang.status = :statusBarang');

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':range', $this->jumlahHari);
        $command->bindValue(':orderPeriod', $this->orderPeriod);
        $command->bindValue(':leadTime', $this->leadTime);
        $command->bindValue(':ssd', $this->ssd);
        $command->bindValue(':statusBarang', Barang::STATUS_AKTIF);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->rakId)) {
            $command->bindValue(':rakId', $this->rakId);
        }
        if (!empty($strukId)) {
            $command->bindValue(':strukId', $strukId);
        }

        // echo $command->getText();
        // Yii::app()->end();
        // return $command->getText();
        return $command->queryAll();
    }

    public function filterKategori()
    {
        return ['NULL' => '[SEMUA]'] + CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterRak()
    {
        return ['NULL' => '[SEMUA]'] + CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function listSortBy()
    {
        return [
            self::SORT_BY_SISA_HARI_ASC => 'Sisa Hari [a-z]',
            self::SORT_BY_SISA_HARI_DSC => 'Sisa Hari [z-a]',
        ];
    }

    public function listNamaSortBy()
    {
        return [
            self::SORT_BY_SISA_HARI_ASC => 'asc',
            self::SORT_BY_SISA_HARI_DSC => 'desc',
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
