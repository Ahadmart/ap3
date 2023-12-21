<?php

/**
 * This is the model class for table "kasir".
 *
 * The followings are the available columns in table 'kasir':
 * @property string $id
 * @property string $user_id
 * @property string $device_id
 * @property string $waktu_buka
 * @property string $waktu_tutup
 * @property string $saldo_awal
 * @property string $saldo_akhir_seharusnya
 * @property string $saldo_akhir
 * @property string $total_penjualan
 * @property string $total_infaq
 * @property string $total_diskon_pernota
 * @property string $total_tarik_tunai
 * @property string $total_koincashback_dipakai
 * @property string $total_penerimaan
 * @property string $total_uang_dibayar
 * @property string $total_margin
 * @property string $total_retur
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Device $device
 * @property User $updatedBy
 * @property User $user
 */
class Kasir extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'kasir';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['user_id, device_id, waktu_buka, saldo_awal', 'required', 'message' => '{attribute} harus diisi!'],
            ['user_id, device_id, updated_by', 'length', 'max' => 10],
            ['saldo_awal, saldo_akhir_seharusnya, saldo_akhir, total_penjualan, total_infaq, total_diskon_pernota, total_tarik_tunai, total_koincashback_dipakai, total_penerimaan, total_uang_dibayar, total_margin, total_retur', 'length', 'max' => 18],
            ['waktu_tutup, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, user_id, device_id, waktu_buka, waktu_tutup, saldo_awal, saldo_akhir_seharusnya, saldo_akhir, total_penjualan, total_infaq, total_diskon_pernota, total_tarik_tunai, total_koincashback_dipakai, total_penerimaan, total_uang_dibayar, total_margin, total_retur, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'device'    => [self::BELONGS_TO, 'Device', 'device_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
            'user'      => [self::BELONGS_TO, 'User', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                         => 'ID',
            'user_id'                    => 'User',
            'device_id'                  => 'Device',
            'waktu_buka'                 => 'Sejak',
            'waktu_tutup'                => 'Waktu Tutup',
            'saldo_awal'                 => 'Saldo Awal',
            'saldo_akhir_seharusnya'     => 'Saldo Akhir Seharusnya',
            'saldo_akhir'                => 'Saldo Akhir',
            'total_penjualan'            => 'Total Penjualan',
            'total_infaq'                => 'Total Infaq',
            'total_diskon_pernota'       => 'Total Diskon Pernota',
            'total_tarik_tunai'          => 'Total Tarik Tunai',
            'total_koincashback_dipakai' => 'Koin Cashback',
            'total_penerimaan'           => 'Total Penerimaan',
            'total_uang_dibayar'         => 'Total Uang Dibayar',
            'total_margin'               => 'Total Margin',
            'total_retur'                => 'Total Retur Jual',
            'updated_at'                 => 'Updated At',
            'updated_by'                 => 'Updated By',
            'created_at'                 => 'Created At',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('device_id', $this->device_id, true);
        $criteria->compare('waktu_buka', $this->waktu_buka, true);
        $criteria->compare('waktu_tutup', $this->waktu_tutup, true);
        $criteria->compare('saldo_awal', $this->saldo_awal, true);
        $criteria->compare('saldo_akhir_seharusnya', $this->saldo_akhir_seharusnya, true);
        $criteria->compare('saldo_akhir', $this->saldo_akhir, true);
        $criteria->compare('total_penjualan', $this->total_penjualan, true);
        $criteria->compare('total_infaq', $this->total_infaq, true);
        $criteria->compare('total_diskon_pernota', $this->total_diskon_pernota, true);
        $criteria->compare('total_tarik_tunai', $this->total_tarik_tunai, true);
        $criteria->compare('total_koincashback_dipakai', $this->total_koincashback_dipakai, true);
        $criteria->compare('total_penerimaan', $this->total_penerimaan, true);
        $criteria->compare('total_penerimaan', $this->total_uang_dibayar, true);
        $criteria->compare('total_margin', $this->total_margin, true);
        $criteria->compare('total_retur', $this->total_retur, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $config      = Config::model()->find('nama=:nama', [':nama' => 'kasir.showhistory']);
        $showHistory = isset($config) ? $config->nilai : 0;
        /* Tampilkan hanya kasir yang masih buka (belum ditutup) */
        if (!$showHistory) {
            $criteria->addCondition('waktu_tutup is null');
        }

        $sort = [
            'defaultOrder' => 'id desc'
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Kasir the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->waktu_buka = date('Y-m-d H:i:s');
        }
        return parent::beforeValidate();
    }

    public function totalPenjualan()
    {
        $command = Yii::app()->db->createCommand('
            select sum(d.jumlah) jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal>=:waktu and penjualan.updated_by=:userId
        ');

        $command->bindValues([
            ':waktu'             => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
            ':userId'            => $this->user_id
        ]);

        return $command->queryRow();
    }

    public function totalMargin()
    {
        $command = Yii::app()->db->createCommand('
            select sum(jual_detail.harga_jual * hpp.qty) - sum(hpp.harga_beli * hpp.qty) jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal>=:waktu and penjualan.updated_by=:userId
            join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
            join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
        ');

        $command->bindValues([
            ':waktu'             => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
            ':userId'            => $this->user_id
        ]);

        return $command->queryRow();
    }

    public function totalReturJual()
    {
        $command = Yii::app()->db->createCommand('
            select sum(d.jumlah) jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal>=:waktu and retur_penjualan.updated_by=:userId
        ');

        $command->bindValues([
            ':waktu'             => $this->waktu_buka,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':userId'            => $this->user_id
        ]);

        return $command->queryRow();
    }

    public function totalTarikTunai()
    {
        $command = Yii::app()->db->createCommand('
        SELECT 
            SUM(jumlah) total
        FROM
            penjualan_tarik_tunai
                JOIN
            penjualan ON penjualan.id = penjualan_tarik_tunai.penjualan_id
                AND penjualan.tanggal >= :waktu
                AND penjualan.updated_by = :userId
        ');

        $command->bindValues([
            ':waktu'  => $this->waktu_buka,
            ':userId' => $this->user_id
        ]);

        return $command->queryRow();
    }

    public function rekapText()
    {
        $jumlahKolom = 40;

        $text            = '';
        $terPanjang      = 0;
        $saldoAwal       = is_null($this->saldo_awal) ? '0' : number_format($this->saldo_awal, 0, ',', '.');
        $terPanjang      = strlen($saldoAwal) > $terPanjang ? strlen($saldoAwal) : $terPanjang;
        $totalPenjualan  = is_null($this->total_penjualan) ? '0' : number_format($this->total_penjualan, 0, ',', '.');
        $terPanjang      = strlen($totalPenjualan) > $terPanjang ? strlen($totalPenjualan) : $terPanjang;
        $totalMargin     = is_null($this->total_margin) ? '0' : number_format($this->total_margin, 0, ',', '.');
        $terPanjang      = strlen($totalMargin) > $terPanjang ? strlen($totalMargin) : $terPanjang;
        $totalRetur      = is_null($this->total_retur) ? '0' : number_format($this->total_retur, 0, ',', '.');
        $terPanjang      = strlen($totalRetur) > $terPanjang ? strlen($totalRetur) : $terPanjang;
        $saldoAkhir      = is_null($this->saldo_akhir_seharusnya) ? '0' : number_format($this->saldo_akhir_seharusnya, 0, ',', '.');
        $terPanjang      = strlen($saldoAkhir) > $terPanjang ? strlen($saldoAkhir) : $terPanjang;
        $saldoAkhirFisik = is_null($this->saldo_akhir) ? '0' : number_format($this->saldo_akhir, 0, ',', '.');
        $terPanjang      = strlen($saldoAkhirFisik) > $terPanjang ? strlen($saldoAkhirFisik) : $terPanjang;
        $selisihSaldo    = is_null($this->saldo_akhir - $this->saldo_akhir_seharusnya) ? '0' : number_format($this->saldo_akhir - $this->saldo_akhir_seharusnya, 0, ',', '.');
        $terPanjang      = strlen($selisihSaldo) > $terPanjang ? strlen($selisihSaldo) : $terPanjang;
        $totalPenerimaan = is_null($this->total_penerimaan) ? number_format($this->penerimaanNet()['total'], 0, ',', '.') : number_format($this->total_penerimaan, 0, ',', '.');
        $terPanjang      = strlen($totalPenerimaan) > $terPanjang ? strlen($totalPenerimaan) : $terPanjang;
        $totalTarikTunai = is_null($this->total_tarik_tunai) ? '0' : number_format($this->total_tarik_tunai, 0, ',', '.');
        $terPanjang      = strlen($totalTarikTunai) > $terPanjang ? strlen($totalTarikTunai) : $terPanjang;
        $totalKoinCB     = is_null($this->total_koincashback_dipakai) ? '0' : number_format($this->total_koincashback_dipakai, 0, ',', '.');
        $terPanjang      = strlen($totalKoinCB) > $terPanjang ? strlen($totalKoinCB) : $terPanjang;

        $text .= str_pad('Login', 19, ' ', STR_PAD_LEFT) . ': ' . $this->user->nama . PHP_EOL;
        $text .= str_pad('Nama', 19, ' ', STR_PAD_LEFT) . ': ' . $this->user->nama_lengkap . PHP_EOL;
        $text .= str_pad('POS Client', 19, ' ', STR_PAD_LEFT) . ': ' . $this->device->nama . PHP_EOL;
        $text .= str_pad('Buka', 19, ' ', STR_PAD_LEFT) . ': ' . date_format(date_create_from_format('Y-m-d H:i:s', $this->waktu_buka), 'd-m-Y H:i:s') . PHP_EOL;
        $text .= str_pad('Tutup', 19, ' ', STR_PAD_LEFT) . ': ' . date_format(date_create_from_format('Y-m-d H:i:s', $this->waktu_tutup), 'd-m-Y H:i:s') . PHP_EOL;
        $text .= str_pad('Saldo Awal', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($saldoAwal, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        $text .= str_pad('Total Penjualan', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalPenjualan, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        $text .= str_pad('Total Margin', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalMargin, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;

        $totalDiskonPerNota = is_null($this->total_diskon_pernota) ? $this->totalDiskonPerNota()['total'] : $this->total_diskon_pernota;
        if ($totalDiskonPerNota > 0) {
            $text .= str_pad('Total Dis Nota', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad(number_format($totalDiskonPerNota, 0, ',', '.'), $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        $totalInfaq = is_null($this->total_infaq) ? $this->totalInfaq()['total'] : $this->total_infaq;
        if ($totalInfaq > 0) {
            $text .= str_pad('Total Infaq', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad(number_format($totalInfaq, 0, ',', '.'), $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        if ($totalPenjualan != $totalPenerimaan) {
            $text .= str_pad('Total Penerimaan', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalPenerimaan, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        //        $penjualanPerAkun = $this->penjualanPerAkun();
        //        if (count($penjualanPerAkun) > 1) {
        //            $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        //            foreach ($penjualanPerAkun as $akun) {
        //                $jumlahAkun = is_null($akun['jumlah']) ? '0' : number_format($akun['jumlah'], 0, ',', '.');
        //                $text       .= str_pad($akun['nama'], 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($jumlahAkun, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        //            }
        //            $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        //        }

        $uangDibayarPerAkun = $this->uangDibayarPerAkun();
        //        if (count($uangDibayarPerAkun) > 1) {
        $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        foreach ($uangDibayarPerAkun as $akun) {
            if ($akun['kb'] == KasBank::KAS_ID) {
                // Jika total_penerimaan null berarti dibuat sebelum update ini, maka hitung ulang
                $totalUangDibayar = is_null($this->total_uang_dibayar) ? $this->totalUangDibayar()['total'] : $this->total_uang_dibayar;
                $totalPenerimaan  = is_null($this->total_penerimaan) ? $this->penerimaanNet()['total'] : $this->total_penerimaan;
                $kas              = $akun['total'] - ($totalUangDibayar - $totalPenerimaan);
                //                    $kas             = $akun['total'];
                $jumlahAkun       = is_null($kas) ? '0' : number_format($kas, 0, ',', '.');
                $text .= str_pad($akun['nama'], 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($jumlahAkun, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
            } else {
                $jumlahAkun = is_null($akun['total']) ? '0' : number_format($akun['total'], 0, ',', '.');
                $text .= str_pad($akun['nama'], 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($jumlahAkun, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
            }
        }
        $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        //        }

        if ($totalTarikTunai > 0) {
            $text .= str_pad('Total Tarik Tunai', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalTarikTunai, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        }

        $tarikTunaiPerAkun = $this->tarikTunaiPerAkun();
        if (!empty($tarikTunaiPerAkun)) {
            $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
            foreach ($tarikTunaiPerAkun as $trkTunai) {
                $jumlahAkun = number_format($trkTunai['jumlah'], 0, ',', '.');
                $text .= str_pad($trkTunai['nama'], 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($jumlahAkun, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
            }
            $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        }

        if ($totalKoinCB > 0) {
            $text .= str_pad('Koin Cashback', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalKoinCB, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
            $text .= str_pad('', 40, '-', STR_PAD_LEFT) . PHP_EOL;
        }
        if ($totalRetur > 0) {
            $text .= str_pad('Total Retur Jual', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($totalRetur, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        }
        $text .= str_pad('Saldo Akhir Kas', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($saldoAkhir, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        $text .= str_pad('Saldo Akhir Fisik', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($saldoAkhirFisik, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        $text .= str_pad('Selisih', 19, ' ', STR_PAD_LEFT) . ': ' . str_pad($selisihSaldo, $terPanjang, ' ', STR_PAD_LEFT) . PHP_EOL;
        return $text;
    }

    /*
      public function penjualanPerAkun()
      {
      $sql = "
      SELECT
      kas_bank.id, kas_bank.nama, t_rekap.jumlah
      FROM
      (SELECT
      p.kas_bank_id, SUM(d.jumlah) jumlah
      FROM
      penerimaan_detail d
      JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
      JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
      AND hp.asal = :hpAsal
      JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
      AND penjualan.tanggal >= :waktuBuka
      AND penjualan.tanggal <= :waktuTutup
      AND penjualan.updated_by = :userId
      GROUP BY p.kas_bank_id) AS t_rekap
      JOIN
      kas_bank ON kas_bank.id = t_rekap.kas_bank_id
      order by kas_bank.nama
      ";

      $command = Yii::app()->db->createCommand($sql);

      $command->bindValues([
      ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
      ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
      ':waktuBuka'        => $this->waktu_buka,
      ':waktuTutup'       => $this->waktu_tutup,
      ':userId'           => $this->user_id,
      ]);

      return $command->queryAll();
      }
     *
     */

    public function uangDibayarPerAkun()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            t.kb, kas_bank.nama, t.total
        FROM
            (SELECT 
                tr.kb, SUM(tr.nominal) total
            FROM
                (SELECT 
                tp.nomor,
                    CASE 
                        WHEN kb2 > 0 THEN kb2
                        ELSE kb1
                    END kb,
                    CASE 
                        WHEN jumlah > 0 THEN jumlah
                        ELSE uang_dibayar
                    END nominal
            FROM
                (SELECT 
                p.nomor,
                    p.kas_bank_id kb1,
                    p.uang_dibayar,
                    pkb.kas_bank_id kb2,
                    pkb.jumlah
            FROM
                penerimaan p
            LEFT JOIN penerimaan_kas_bank pkb ON p.id = pkb.penerimaan_id
            WHERE
                p.id IN (SELECT DISTINCT
                        p.id
                    FROM
                        penerimaan_detail d
                    JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                    JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                    JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)) AS tp) AS tr
            GROUP BY tr.kb) AS t
                JOIN
            kas_bank ON t.kb = kas_bank.id
            ORDER BY t.kb
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
            ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'        => $this->waktu_buka,
            ':userId'           => $this->user_id,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryAll();
    }

    public function penerimaanNet()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            SUM(CASE posisi WHEN 0 THEN jumlah ELSE -jumlah END) total
        FROM
            penerimaan_detail
        WHERE
            penerimaan_id IN (SELECT DISTINCT
                    p.id
                FROM
                    penerimaan_detail d
                        JOIN
                    penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                        JOIN
                    hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                        JOIN
                    penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
            ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'        => $this->waktu_buka,
            ':userId'           => $this->user_id,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryRow();
    }

    public function totalUangDibayar()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            SUM(uang_dibayar) total
        FROM
            penerimaan
        WHERE
            id IN (SELECT DISTINCT
                    p.id
                FROM
                    penerimaan_detail d
                        JOIN
                    penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                        JOIN
                    hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                        JOIN
                    penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
            ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'        => $this->waktu_buka,
            ':userId'           => $this->user_id,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryRow();
    }

    public function totalInfaq()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            SUM(jumlah) total
        FROM
            penerimaan_detail
        WHERE
            penerimaan_id IN (SELECT DISTINCT
                    p.id
                FROM
                    penerimaan_detail d
                        JOIN
                    penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                        JOIN
                    hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                        JOIN
                    penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)
        AND item_id = :itemInfaq
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
            ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'        => $this->waktu_buka,
            ':userId'           => $this->user_id,
            ':itemInfaq'        => ItemKeuangan::POS_INFAQ,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryRow();
    }

    public function totalDiskonPerNota()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            SUM(jumlah) total
        FROM
            penerimaan_detail
        WHERE
            penerimaan_id IN (SELECT DISTINCT
                    p.id
                FROM
                    penerimaan_detail d
                        JOIN
                    penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                        JOIN
                    hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                        JOIN
                    penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)
        AND item_id = :itemInfaq
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus' => Penerimaan::STATUS_BAYAR,
            ':hpAsal'           => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'        => $this->waktu_buka,
            ':userId'           => $this->user_id,
            ':itemInfaq'        => ItemKeuangan::POS_DISKON_PER_NOTA,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryRow();
    }

    public function totalPenerimaanKas()
    {
        $uangDibayarPerAkun = $this->uangDibayarPerAkun();
        $jumlahAkun         = 0;
        // print_r($uangDibayarPerAkun);
        foreach ($uangDibayarPerAkun as $akun) {
            if ($akun['kb'] == KasBank::KAS_ID) {
                // Jika total_penerimaan null berarti dibuat sebelum update ini, maka hitung ulang
                $totalUangDibayar = is_null($this->total_uang_dibayar) ? $this->totalUangDibayar()['total'] : $this->total_uang_dibayar;
                $totalPenerimaan  = is_null($this->total_penerimaan) ? $this->penerimaanNet()['total'] : $this->total_penerimaan;
                $kas              = $akun['total'] - ($totalUangDibayar - $totalPenerimaan);
                $jumlahAkun       = is_null($kas) ? '0' : $kas;
            }
        }
        return $jumlahAkun;
    }

    public function tarikTunaiPerAkun()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND t.updated_at <= :waktuTutup';
        }
        $sql = "
        SELECT 
            t_rekap_akun.kas_bank_id, t_rekap_akun.jumlah, kas_bank.nama
        FROM
            (SELECT 
                kas_bank_id, SUM(jumlah) jumlah
            FROM
                penjualan_tarik_tunai t
            WHERE
                t.updated_by = :userId
                    AND t.updated_at >= :waktuBuka
                    {$kondisiTutup}
            GROUP BY kas_bank_id) t_rekap_akun
                JOIN
            kas_bank ON kas_bank.id = t_rekap_akun.kas_bank_id
        ORDER BY kas_bank.id
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':waktuBuka' => $this->waktu_buka,
            ':userId'    => $this->user_id,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryAll();
    }

    public function totalKoinCashbackDipakai()
    {
        $kondisiTutup = '';
        if (!is_null($this->waktu_tutup)) {
            $kondisiTutup = 'AND penjualan.tanggal <= :waktuTutup';
        }
        $sql = "
        SELECT 
            SUM(CASE posisi WHEN 1 THEN jumlah ELSE -jumlah END) total
        FROM
            penerimaan_detail
        WHERE
            penerimaan_id IN (SELECT DISTINCT
                    p.id
                FROM
                    penerimaan_detail d
                        JOIN
                    penerimaan p ON d.penerimaan_id = p.id AND p.status = :penerimaanStatus
                        JOIN
                    hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :hpAsal
                        JOIN
                    penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :waktuBuka
                        {$kondisiTutup}
                        AND penjualan.updated_by = :userId)
                AND item_id = :itemKeuKoinDipakai
        ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':penerimaanStatus'   => Penerimaan::STATUS_BAYAR,
            ':hpAsal'             => HutangPiutang::DARI_PENJUALAN,
            ':waktuBuka'          => $this->waktu_buka,
            ':userId'             => $this->user_id,
            ':itemKeuKoinDipakai' => ItemKeuangan::POS_KOINCASHBACK_DIPAKAI,
        ]);
        if (!is_null($this->waktu_tutup)) {
            $command->bindValue(':waktuTutup', $this->waktu_tutup);
        }

        return $command->queryRow();
    }

    public static function sedangBuka($userId)
    {
        return Kasir::model()->find('waktu_tutup is null AND user_id = :userId', [':userId' => $userId]);
    }
}
