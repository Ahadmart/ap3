<?php

/**
 * ReportPenjualanPerStrukturForm class.
 * ReportPenjualanPerStrukturForm is the data structure for keeping
 * report penjualan form data. It is used by the 'penjualanperstruktur' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportPenjualanPerStrukturForm extends CFormModel
{
    const KERTAS_LETTER = 10;
    const KERTAS_A4     = 20;
    const KERTAS_FOLIO  = 30;
    /* ===================== */
    const KERTAS_LETTER_NAMA = 'Letter';
    const KERTAS_A4_NAMA     = 'A4';
    const KERTAS_FOLIO_NAMA  = 'Folio';

    public $profilId;
    public $userId;
    public $dari;
    public $sampai;
    public $strukLv1;
    public $strukLv2;
    public $strukLv3;
    public $kertas;
    public $printer;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return [
            ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
            // ['dari', 'compare', 'compareAttribute' => 'sampai', 'operator' => '<=','message' => '[Tanggal dari] tidak boleh lebih besar dari [tanggal sampai]'],
            ['profilId, userId, strukLv1, strukLv2, strukLv3, kertas, printer', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'profilId' => 'Profil',
            'userId'   => 'User',
            'dari'     => 'Dari',
            'sampai'   => 'Sampai',
            'strukLv1' => 'Struktur Level 1',
            'strukLv2' => 'Struktur Level 2',
            'strukLv3' => 'Struktur Level 3',
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

    public function reportDetailPerStrukturLv3($strukturId, $hideOpenTxn)
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s');

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
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

        $userId = Yii::app()->user->id;
        $sql    = "
        SELECT
            t_penjualan.barang_id,
            barang.barcode,
            barang.nama,
            barang.struktur_id,
            bs.nama nama_struktur,
            t_penjualan.totalqty qty,
            t_penjualan.total omzet,
            t_modal.total modal,
            (t_penjualan.total - t_modal.total) margin,
            t_stok.stok
        FROM
            (SELECT
                pd.barang_id,
                    SUM(pd.qty) totalqty,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_penjualan
                JOIN
            (SELECT
                pd.barang_id, SUM(hpp.qty * hpp.harga_beli) total
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_modal ON t_penjualan.barang_id = t_modal.barang_id
                JOIN
            barang ON barang.id = t_penjualan.barang_id
                AND barang.struktur_id = :strukturId
                JOIN
            (SELECT
                barang_id, SUM(qty) stok
            FROM
                inventory_balance
            GROUP BY barang_id
            -- HAVING SUM(qty) > 0
            ) t_stok ON t_stok.barang_id = barang.id
                LEFT JOIN
            barang_struktur bs ON bs.id = barang.struktur_id
        ORDER BY bs.nama , barang.barcode
                ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);
        $command->bindValue(':strukturId', $strukturId);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }

        return $command->queryAll();
    }

    public function reportDetail($hideOpenTxn = false)
    {
        $strukturList = [];
        if ($this->strukLv3 > 0) {
            /* Sales by Struktur Lv 3 */
            $strukturList[] = $this->strukLv3;
        } elseif ($this->strukLv2 > 0) {
            /* Sales by Struktur Lv 2 */
            $strukturList = $this->listChildStruk($this->strukLv2);
        }

        $r = [];
        foreach ($strukturList as $strukId) {
            $r[$strukId] = $this->reportDetailPerStrukturLv3($strukId, $hideOpenTxn);
        }
        return $r;
    }

    public static function listStrukLv1()
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=1 AND status=:publish';
        $criteria->params    = [':publish' => StrukturBarang::STATUS_PUBLISH];
        $criteria->order     = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(StrukturBarang::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listStrukLv2($parentId)
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=2 AND status=:publish AND parent_id=:parentId';
        $criteria->params    = [
            ':publish'  => StrukturBarang::STATUS_PUBLISH,
            ':parentId' => $parentId,
        ];
        $criteria->order = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(StrukturBarang::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listStrukLv3($parentId)
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'level=3 AND status=:publish AND parent_id=:parentId';
        $criteria->params    = [
            ':publish'  => StrukturBarang::STATUS_PUBLISH,
            ':parentId' => $parentId,
        ];
        $criteria->order = 'nama';

        return ['' => '[SEMUA]'] + CHtml::listData(StrukturBarang::model()->findAll($criteria), 'id', 'nama');
    }

    public static function listKertas()
    {
        return [
            self::KERTAS_A4     => self::KERTAS_A4_NAMA,
            self::KERTAS_FOLIO  => self::KERTAS_FOLIO_NAMA,
            self::KERTAS_LETTER => self::KERTAS_LETTER_NAMA,
        ];
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

    /**
     * Report Penjualan group by struktur Lv 2
     * @param string $strukLv3CS (Comma separated dari Struk Lv 3)
     * @return array
     */
    public function reportPerStrukturLv2($strukLv3CS, $hideOpenTxn)
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s');

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
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

        // $userId = Yii::app()->user->id;
        $sql = "
        SELECT
            bs2.id lv2_id,
            bs2.nama lv2_nama,
            SUM(t_penjualan.totalqty) qty,
            SUM(t_penjualan.total) omzet,
            SUM(t_modal.total) modal,
            SUM(t_penjualan.total - t_modal.total) margin,
            AVG((t_penjualan.total - t_modal.total) / t_penjualan.total * 100) profit_margin,
            SUM(t_stok.stok) stok
        FROM
            (SELECT
                pd.barang_id,
                    SUM(pd.qty) totalqty,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_penjualan
                JOIN
            (SELECT
                pd.barang_id, SUM(hpp.qty * hpp.harga_beli) total
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_modal ON t_penjualan.barang_id = t_modal.barang_id
                JOIN
            barang ON barang.id = t_penjualan.barang_id
                AND barang.struktur_id IN ({$strukLv3CS})
                JOIN
            (SELECT
                barang_id, SUM(qty) stok
            FROM
                inventory_balance
            GROUP BY barang_id
            -- HAVING SUM(qty) > 0
            ) t_stok ON t_stok.barang_id = barang.id
                LEFT JOIN
            barang_struktur bs ON bs.id = barang.struktur_id
                LEFT JOIN
            barang_struktur bs2 ON bs2.id = bs.parent_id
        GROUP BY bs2.id
        ORDER BY bs2.nama
                ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }

        return $command->queryAll();
    }

    /**
     * Report Penjualan barang tanpa struktur mengikuti format report per struktur Lv 2
     * @return array
     */
    public function reportTanpaStrukturLv2($hideOpenTxn)
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s');

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
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

        $sql = "
        SELECT
            0 lv2_id,
            'Tanpa Struktur' lv2_nama,
            SUM(t_penjualan.totalqty) qty,
            SUM(t_penjualan.total) omzet,
            SUM(t_modal.total) modal,
            SUM(t_penjualan.total - t_modal.total) margin,
            AVG((t_penjualan.total - t_modal.total) / t_penjualan.total * 100) profit_margin,
            SUM(t_stok.stok) stok
        FROM
            (SELECT
                pd.barang_id,
                    SUM(pd.qty) totalqty,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_penjualan
                JOIN
            (SELECT
                pd.barang_id, SUM(hpp.qty * hpp.harga_beli) total
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != :statusDraft
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
                ${hideOpenTxnJoin}
                ${hideOpenTxnCond}
            GROUP BY pd.barang_id) t_modal ON t_penjualan.barang_id = t_modal.barang_id
                JOIN
            barang ON barang.id = t_penjualan.barang_id
                AND barang.struktur_id IS NULL
                JOIN
            (SELECT
                barang_id, SUM(qty) stok
            FROM
                inventory_balance
            GROUP BY barang_id
            -- HAVING SUM(qty) > 0
            ) t_stok ON t_stok.barang_id = barang.id
                ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }

        return $command->queryAll();
    }

    /**
     * Sales by Struktur Lv 2
     */
    public function reportPerLv2($hideOpenTxn = false)
    {
        $reportTanpaStruktur = [];
        /* List Struktur Lv 1 */
        $strukturList = [];
        if ($this->strukLv1 > 0) {
            $strukturList[] = $this->strukLv1;
        } else {
            $strukturList        = $this->listChildStruk(null);
            $reportTanpaStruktur = $this->reportTanpaStrukturLv2($hideOpenTxn);
        }
        //        echo '<pre>';
        //        var_dump($strukturList);
        //        exit();

        $r = [];
        foreach ($strukturList as $strukId) {
            $strukturListLv2 = $this->listChildStruk($strukId);
            $strukLv3        = [];
            //            echo '$lv1: ' . $strukId . '<br />listlv2' . print_r($strukturListLv2, true) . '<br />';
            foreach ($strukturListLv2 as $lv2) {
                //                echo '$lv2: ' . $lv2 . '<br />' . 'listlv3: ' . print_r($this->listChildStruk($lv2), true) . '<br />';
                $strukLv3 = array_merge($strukLv3, $this->listChildStruk($lv2));
                //                echo '<hr />';
            }
            $strukLv3CS = implode(',', $strukLv3);
            //            echo $strukLv3CS;
            //            echo '<hr />';
            if (!empty($strukLv3CS)) {
                $r[$strukId] = $this->reportPerStrukturLv2($strukLv3CS, $hideOpenTxn);
            }
        }
        if (!empty($reportTanpaStruktur)) {
            $r['Tanpa Struktur'] = $this->reportTanpaStrukturLv2($hideOpenTxn);
        }
        //            echo '</pre>';
        return $r;
    }

    public function daftarStrukLv1()
    {
        $criteria            = new CDbCriteria();
        $criteria->condition = 'status=:publish AND parent_id IS NULL';
        $criteria->params    = [
            ':publish' => StrukturBarang::STATUS_PUBLISH,
            // ':id'      => $id
        ];
        $criteria->order = 'nama';

        $childStruk = StrukturBarang::model()->findAll($criteria);

        $r = [];
        foreach ($childStruk as $struk) {
            $r[] = $struk->id;
        }
        return $r;
    }

    public function reportDetailCsv($lv1Id = null, $lv2Id = null, $lv3Id = null)
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s');

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
        }

        $lv3Cond = '';
        if (!is_null($lv3Id)) {
            $lv3Cond = ' AND bs3.id = :lv3Id';
        }

        $lv2Cond = '';
        if (!is_null($lv2Id)) {
            $lv2Cond = ' AND bs2.id = :lv2Id';
        }

        $lv1Cond = '';
        if (!is_null($lv1Id)) {
            $lv1Cond = ' AND bs1.id = :lv1Id';
        }

        $sql = "
        SELECT
            barang.barcode,
            barang.nama,
            bs1.nama struktur_lv1,
            bs2.nama struktur_lv2,
            bs3.nama struktur_lv3,
            t_penjualan.totalqty qty,
            t_penjualan.total omzet,
            t_modal.total modal,
            (t_penjualan.total - t_modal.total) margin,
            t_stok.stok
        FROM
            (SELECT
                pd.barang_id,
                    SUM(pd.qty) totalqty,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != 0
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
            GROUP BY pd.barang_id) t_penjualan
                JOIN
            (SELECT
                pd.barang_id, SUM(hpp.qty * hpp.harga_beli) total
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != 0
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
            GROUP BY pd.barang_id) t_modal ON t_penjualan.barang_id = t_modal.barang_id
                JOIN
            barang ON barang.id = t_penjualan.barang_id
                JOIN
            (SELECT
                barang_id, SUM(qty) stok
            FROM
                inventory_balance
            GROUP BY barang_id) t_stok ON t_stok.barang_id = barang.id
                JOIN
            barang_struktur bs3 ON bs3.id = barang.struktur_id {$lv3Cond}
                JOIN
            barang_struktur bs2 ON bs2.id = bs3.parent_id {$lv2Cond}
                JOIN
            barang_struktur bs1 ON bs1.id = bs2.parent_id {$lv1Cond}
        ORDER BY bs1.nama , bs2.nama , bs3.nama , barang.barcode
                ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }
        if (!is_null($lv3Id)) {
            $command->bindValue(':lv3Id', $lv3Id);
        }
        if (!is_null($lv2Id)) {
            $command->bindValue(':lv2Id', $lv2Id);
        }
        if (!is_null($lv1Id)) {
            $command->bindValue(':lv1Id', $lv1Id);
        }

        return $command->queryAll();
    }

    public function reportDetailCsvTanpaStruktur()
    {
        $dari   = date_format(date_create_from_format('d-m-Y H:i', $this->dari), 'Y-m-d H:i:s');
        $sampai = date_format(date_create_from_format('d-m-Y H:i', $this->sampai), 'Y-m-d H:i:s');

        $whereSub = '';
        if (!empty($this->profilId)) {
            $whereSub .= ' AND pj.profil_id = :profilId';
        }

        if (!empty($this->userId)) {
            $whereSub .= ' AND pj.updated_by = :userId';
        }

        $sql = "
        SELECT
            barang.barcode,
            barang.nama,
            'NULL' struktur_lv1,
            'NULL' struktur_lv2,
            'NULL' struktur_lv3,
            t_penjualan.totalqty qty,
            t_penjualan.total omzet,
            t_modal.total modal,
            (t_penjualan.total - t_modal.total) margin,
            t_stok.stok
        FROM
            (SELECT
                pd.barang_id,
                    SUM(pd.qty) totalqty,
                    SUM(pd.harga_jual * pd.qty) total
            FROM
                penjualan_detail pd
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != 0
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
            GROUP BY pd.barang_id) t_penjualan
                JOIN
            (SELECT
                pd.barang_id, SUM(hpp.qty * hpp.harga_beli) total
            FROM
                harga_pokok_penjualan hpp
            JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
            JOIN penjualan pj ON pd.penjualan_id = pj.id
                AND pj.status != 0
                AND pj.tanggal BETWEEN :dari AND :sampai
                {$whereSub}
            GROUP BY pd.barang_id) t_modal ON t_penjualan.barang_id = t_modal.barang_id
                JOIN
            barang ON barang.id = t_penjualan.barang_id AND barang.struktur_id IS NULL
                JOIN
            (SELECT
                barang_id, SUM(qty) stok
            FROM
                inventory_balance
            GROUP BY barang_id) t_stok ON t_stok.barang_id = barang.id
        ORDER BY barang.barcode
                ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $command->bindValue(':dari', $dari);
        $command->bindValue(':sampai', $sampai);

        if (!empty($this->profilId)) {
            $command->bindValue(':profilId', $this->profilId);
        }
        if (!empty($this->userId)) {
            $command->bindValue(':userId', $this->userId);
        }

        return $command->queryAll();
    }

    public function reportCSV()
    {
        $r = [];
        if ($this->strukLv3 > 0) {
            $r = $this->reportDetailCsv(null, null, $this->strukLv3);
        } elseif ($this->strukLv2 > 0) {
            /* Sales by Struktur Lv 2 */
            $r = $this->reportDetailCsv(null, $this->strukLv2, null);
        } elseif ($this->strukLv1 > 0) {
            /* Sales by Struktur Lv 1 */
            $r = $this->reportDetailCsv($this->strukLv1, null, null);
        } else {
            /* Semua sales, yang ada strukturnya dan yang tidak */
            $r = array_merge($this->reportDetailCsv(null, null, null), $this->reportDetailCsvTanpaStruktur());
        }
        return $this->array2csv($r);
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
