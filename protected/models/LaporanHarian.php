<?php

/**
 * This is the model class for table "laporan_harian".
 *
 * The followings are the available columns in table 'laporan_harian':
 * @property string $id
 * @property string $tanggal
 * @property string $saldo_akhir
 * @property string $keterangan
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class LaporanHarian extends CActiveRecord
{
    public $tanggalAwal;
    public $tanggalAkhir;
    public $groupByProfil = ['inv' => false, 'keu' => false];

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'laporan_harian';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['tanggal', 'required'],
            ['tanggal', 'unique'],
            ['saldo_akhir', 'length', 'max' => 18],
            ['keterangan', 'length', 'max' => 5000],
            ['updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, tanggal, saldo_akhir, keterangan, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'tanggal'     => 'Tanggal',
            'saldo_akhir' => 'Saldo Akhir Asli',
            'keterangan'  => 'Remarks',
            'updated_at'  => 'Updated At',
            'updated_by'  => 'Updated By',
            'created_at'  => 'Created At',
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
        $criteria->compare('tanggal', $this->tanggal, true);
        $criteria->compare('saldo_akhir', $this->saldo_akhir, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LaporanHarian the static model class
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
        $this->tanggal = !empty($this->tanggal) ? date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d') : null;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal     = !is_null($this->tanggal) ? date_format(
            date_create_from_format('Y-m-d', $this->tanggal),
            'd-m-Y'
        ) : '0';
        $this->saldo_akhir = number_format($this->saldo_akhir, 0, false, false);
        return parent::afterFind();
    }

    public function saldoAwal()
    {
        $command = Yii::app()->db->createCommand('
         select harian.saldo_akhir
         from laporan_harian harian
         where tanggal=(select tanggal from laporan_harian where tanggal < :tanggal and saldo_akhir is not null order by tanggal desc limit 1)
              ');
        $command->bindValue(':tanggal', $this->tanggal);
        $harian  = $command->queryRow();
        return $harian ? $harian['saldo_akhir'] : Config::model()->find("nama='keuangan.saldo_awal'")->nilai;
    }

    public function saldoAkhir()
    {
        $pengeluaran      = $this->itemPengeluaran();
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $kategoriPengeluaran) {
            $totalPengeluaran += $kategoriPengeluaran['total'];
        }

        $penerimaan      = $this->itemPenerimaan();
        $totalPenerimaan = 0;
        foreach ($penerimaan as $kategoriPenerimaan) {
            $totalPenerimaan += $kategoriPenerimaan['total'];
        }
        return $this->saldoAwal() //
            - $this->totalPembelianBayar() //
            - $this->totalPembelianTunai() //
            - $this->totalReturJualBayar() //
            - $this->totalReturJualTunai() //
            + $this->totalPenjualanBayar() //
            + $this->totalPenjualanTunai() //
            + $this->totalReturBeliBayar() //
            + $this->totalReturBeliTunai() //
            - $totalPengeluaran //
            + $totalPenerimaan;
        /*
          return $this->saldoAwal() //
          .'-'. $this->totalPembelianBayar() //
          .'-'.$this->totalPembelianTunai() //
          .'-'.$this->totalReturJualBayar() //
          .'-'.$this->totalReturJualTunai() //
          .'+'. $this->totalPenjualanBayar() //
          .'+'. $this->totalPenjualanTunai() //
          .'+'. $this->totalReturBeliBayar() //
          .'+'. $this->totalReturBeliTunai() //
          .'-'.$totalPengeluaran //
          .'+'. $totalPenerimaan;
         */
    }

    /**
     * Pembelian yang dibayar di hari yang sama
     * @return array Pembelian tunai per trx (nomor pembelian, profil, total)
     */
    public function pembelianTunai()
    {
        $sql = '
         select pembelian.nomor, sum(jumlah) jumlah, profil.nama
         FROM
         (
            select pembelian.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id = pembelian.hutang_piutang_id and pembelian.tanggal >= :tanggalAwal and pembelian.tanggal < :tanggalAkhir
            union
            select pembelian.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id = pembelian.hutang_piutang_id and pembelian.tanggal >= :tanggalAwal and pembelian.tanggal < :tanggalAkhir
         ) t
         join pembelian on t.id = pembelian.id
         join profil on pembelian.profil_id = profil.id
         group by t.id
         order by pembelian.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalPembelianTunai()
    {
        $command = Yii::app()->db->createCommand('
        select sum(jumlah) total
         FROM
         (
            select pembelian.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id = pembelian.hutang_piutang_id and pembelian.tanggal >= :tanggalAwal and pembelian.tanggal < :tanggalAkhir
            union
            select pembelian.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id = pembelian.hutang_piutang_id and pembelian.tanggal >= :tanggalAwal and pembelian.tanggal < :tanggalAkhir
         ) t');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $pembelian = $command->queryRow();
        return $pembelian['total'];
    }

    /**
     * Pembelian yang masih hutang
     * @return array Pembelian pada tanggal tsb yang masih hutang per trx (nomor pembelian, profil, total)
     */
    public function pembelianHutang()
    {
        $sql = '
         select pembelian.nomor, profil.nama, t3.jumlah-t3.jml_bayar jumlah
         from
         (
            select pb.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from pembelian pb
            join hutang_piutang hp on pb.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where pb.tanggal >= :tanggalAwal and pb.tanggal < :tanggalAkhir
            group by pb.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3
         join pembelian on t3.id=pembelian.id
         join profil on pembelian.profil_id=profil.id
         order by pembelian.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalPembelianHutang()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t3.jumlah-t3.jml_bayar) total
         from
         (
            select pb.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from pembelian pb
            join hutang_piutang hp on pb.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where pb.tanggal >= :tanggalAwal and pb.tanggal < :tanggalAkhir
            group by pb.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $hutangPembelian = $command->queryRow();
        return $hutangPembelian['total'];
    }

    /**
     * Pembelian yang dibayar pada tanggal tsb, per nomor pembelian
     * @return array nomor pembelian, nama profil, tanggal pembelian, total pembayaran
     */
    public function pembelianBayar()
    {
        $sql = '
         select pembelian.nomor, profil.nama, pembelian.tanggal, t2.total_bayar
         from
         (
            select id, sum(jumlah_bayar) total_bayar
            from
            (
               select sum(pd.jumlah) jumlah_bayar, pembelian.id
               from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
               join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
               join pembelian on hp.id=pembelian.hutang_piutang_id and pembelian.tanggal < :tanggalAwal
               group by pembelian.id
               union
               select sum(pd.jumlah) jumlah_bayar, pembelian.id
               from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
               join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
               join pembelian on hp.id=pembelian.hutang_piutang_id and pembelian.tanggal < :tanggalAwal
               group by pembelian.id
            ) t1
            group by id
         ) t2
         join pembelian on t2.id=pembelian.id
         join profil on pembelian.profil_id = profil.id
         order by pembelian.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(total_bayar) total_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        return $command->queryAll();
    }

    public function totalPembelianBayar()
    {
        $command = Yii::app()->db->createCommand('
         select sum(jumlah_bayar) total
         from
         (
            select sum(pd.jumlah) jumlah_bayar, pembelian.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id=pembelian.hutang_piutang_id and pembelian.tanggal < :tanggalAwal
            group by pembelian.id
            union
            select sum(pd.jumlah) jumlah_bayar, pembelian.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=1
            join pembelian on hp.id=pembelian.hutang_piutang_id and pembelian.tanggal < :tanggalAwal
            group by pembelian.id
         ) t1');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $bayarPembelian = $command->queryRow();
        return $bayarPembelian['total'];
    }

    /**
     * Penjualan tunai yang terjadi pada tanggal tsb
     * @return array nomor, nama, jumlah dari penjualan tunai
     */
    public function penjualanTunai()
    {
        /*
        $sql = "
         select penjualan.nomor, sum(jumlah) jumlah, profil.nama
         FROM
         (
            select penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
            union
            select penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
         ) t
         join penjualan on t.id = penjualan.id
         join profil on penjualan.profil_id = profil.id
         group by t.id
         order by penjualan.nomor";
         *
        $sql = "
         SELECT penjualan.nomor, jumlah, profil.nama, kb.nama nama_akun
         FROM
         (
            SELECT penjualan.id, p.kas_bank_id, d.jumlah
            FROM penerimaan_detail d
            JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status=:statusPenerimaan AND p.tanggal=:tanggal
            JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id AND hp.asal=:asalHutangPiutang
            JOIN penjualan ON hp.id = penjualan.hutang_piutang_id AND penjualan.tanggal >= :tanggalAwal AND penjualan.tanggal < :tanggalAkhir
            UNION
            SELECT penjualan.id, p.kas_bank_id, d.jumlah
            FROM pengeluaran_detail d
            JOIN pengeluaran p ON d.pengeluaran_id = p.id AND p.status=:statusPengeluaran AND p.tanggal=:tanggal
            JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id AND hp.asal=:asalHutangPiutang
            JOIN penjualan ON hp.id = penjualan.hutang_piutang_id AND penjualan.tanggal >= :tanggalAwal AND penjualan.tanggal < :tanggalAkhir
         ) t
         JOIN penjualan ON t.id = penjualan.id
         JOIN profil ON penjualan.profil_id = profil.id
         JOIN kas_bank kb ON kb.id = t.kas_bank_id
         ORDER BY kb.nama, penjualan.nomor";
         */
        $listPenerimaan = '
                    SELECT DISTINCT
                        p.id
                    FROM
                        penerimaan_detail d
                    JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status = :statusPenerimaan
                        AND p.tanggal = :tanggal
                    JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :asalHutangPiutang
                    JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :tanggalAwal
                        AND penjualan.tanggal < :tanggalAkhir            
                ';
        $listPengeluaran = '
                    SELECT DISTINCT
                        p.id
                    FROM
                        pengeluaran_detail d
                    JOIN pengeluaran p ON d.pengeluaran_id = p.id AND p.status = :statusPengeluaran
                        AND p.tanggal = :tanggal
                    JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :asalHutangPiutang
                    JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :tanggalAwal
                        AND penjualan.tanggal < :tanggalAkhir
            ';
        /*
        $sql = "
        SELECT
            tabel_detail.*,
            profil.nama nama,
            kas_bank.nama nama_akun,
            CASE
                WHEN uang_dibayar > 0 THEN uang_dibayar_perakun - (uang_dibayar - penjualan)
                WHEN count IS NULL OR count = 1 THEN penjualan
                ELSE uang_dibayar_perakun
            END jumlah
        FROM
            (SELECT
                t.*,
                    CASE
                        WHEN
                            t.kb = 1 OR t.count IS NULL
                                OR t.count = 1
                        THEN
                            tp.jumlah
                    END penjualan,
                    CASE t.kb
                        WHEN 1 THEN penerimaan.uang_dibayar
                    END uang_dibayar,
                    t_penjualan.nomor
            FROM
                (SELECT
                tr.id,
                    tr.nomor nomor_bayar,
                    tr.profil_id,
                    CASE
                        WHEN kb2 > 0 THEN kb2
                        ELSE kb1
                    END kb,
                    CASE
                        WHEN tr.jumlah > 0 THEN tr.jumlah
                        ELSE uang_dibayar
                    END uang_dibayar_perakun,
                    tr.count
            FROM
                (SELECT
                p.id,
                    p.nomor,
                    p.profil_id,
                    p.kas_bank_id kb1,
                    p.uang_dibayar,
                    pkb.kas_bank_id kb2,
                    pkb.jumlah,
                    tc.count
            FROM
                penerimaan p
            LEFT JOIN penerimaan_kas_bank pkb ON p.id = pkb.penerimaan_id
            LEFT JOIN (SELECT
                penerimaan_kas_bank.penerimaan_id, COUNT(*) count
            FROM
                penerimaan_kas_bank
            WHERE
                penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
            GROUP BY penerimaan_id) tc ON tc.penerimaan_id = p.id
            WHERE
                p.id IN ({$listPenerimaan})
            ORDER BY p.nomor) tr) t
            JOIN (SELECT
                penerimaan_id, SUM(jumlah) jumlah
            FROM
                penerimaan_detail
            WHERE
                penerimaan_detail.item_id = :itemPenjualan
                AND penerimaan_id IN ({$listPenerimaan})
            GROUP BY penerimaan_id) tp ON tp.penerimaan_id = t.id
            JOIN penerimaan ON t.id = penerimaan.id
            JOIN (SELECT
                penerimaan_detail.penerimaan_id, penjualan.nomor
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN penerimaan_detail ON penerimaan_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                penerimaan_id IN ({$listPenerimaan})) t_penjualan ON t_penjualan.penerimaan_id = t.id UNION SELECT
                t.*,
                    CASE
                        WHEN
                            t.kb = 1 OR t.count IS NULL
                                OR t.count = 1
                        THEN
                            tp.jumlah
                    END penjualan,
                    CASE t.kb
                        WHEN 1 THEN pengeluaran.uang_dibayar
                    END uang_dibayar,
                    t_penjualan.nomor
            FROM
                (SELECT
                tr.id,
                    tr.nomor nomor_bayar,
                    tr.profil_id,
                    CASE
                        WHEN kb2 > 0 THEN kb2
                        ELSE kb1
                    END kb,
                    CASE
                        WHEN tr.jumlah > 0 THEN tr.jumlah
                        ELSE uang_dibayar
                    END uang_dibayar_perakun,
                    tr.count
            FROM
                (SELECT
                p.id,
                    p.nomor,
                    p.profil_id,
                    p.kas_bank_id kb1,
                    p.uang_dibayar,
                    pkb.kas_bank_id kb2,
                    pkb.jumlah,
                    tc.count
            FROM
                pengeluaran p
            LEFT JOIN pengeluaran_kas_bank pkb ON p.id = pkb.pengeluaran_id
            LEFT JOIN (SELECT
                pengeluaran_kas_bank.pengeluaran_id, COUNT(*) count
            FROM
                pengeluaran_kas_bank
            WHERE
                pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
            GROUP BY pengeluaran_id) tc ON tc.pengeluaran_id = p.id
            WHERE
                p.id IN ({$listPengeluaran})
            ORDER BY p.nomor) tr) t
            JOIN (SELECT
                pengeluaran_id, SUM(jumlah) jumlah
            FROM
                pengeluaran_detail
            WHERE
                pengeluaran_detail.item_id = :itemPenjualan
                AND pengeluaran_id IN ({$listPengeluaran})
            GROUP BY pengeluaran_id) tp ON tp.pengeluaran_id = t.id
            JOIN pengeluaran ON t.id = pengeluaran.id
            JOIN (SELECT
                pengeluaran_detail.pengeluaran_id, penjualan.nomor
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN pengeluaran_detail ON pengeluaran_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                pengeluaran_id IN ({$listPengeluaran})) t_penjualan ON t_penjualan.pengeluaran_id = t.id) tabel_detail
                JOIN
            profil ON profil.id = tabel_detail.profil_id
                JOIN
            kas_bank ON kas_bank.id = tabel_detail.kb
        ORDER BY kas_bank.nama , tabel_detail.nomor
            ";
*/
        $sql = "
            SELECT 
                kas_bank_id,
                profil_id,
                penjualan_nomor nomor,
                kas_bank.nama nama_akun,
                profil.nama,
                jumlah
            FROM
                (SELECT DISTINCT
                    penjualan_id,
                        penjualan_nomor,
                        profil_id,
                        IFNULL(kas_bank_id, 1) kas_bank_id,
                        CASE
                            WHEN kas_bank_id = 1 OR kas_bank_id IS NULL THEN SUM(jumlah_penerimaan - IFNULL(t_selain_kas.jumlah, 0))
                            ELSE SUM(jumlah_pembayaran)
                        END jumlah
                FROM
                    (SELECT 
                    penerimaan_detail.penerimaan_id penerimaan_id1,
                        penerimaan_detail.jumlah jumlah_penerimaan,
                        penjualan.id penjualan_id,
                        penjualan.nomor penjualan_nomor,
                        penjualan.profil_id
                FROM
                    penjualan
                JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
                JOIN penerimaan_detail ON penerimaan_detail.hutang_piutang_id = hutang_piutang.id
                WHERE
                    penerimaan_id IN ({$listPenerimaan})) AS t_penerimaan_j
                LEFT JOIN (SELECT 
                    penerimaan_id penerimaan_id2,
                        kas_bank_id,
                        jumlah jumlah_pembayaran
                FROM
                    penerimaan_kas_bank
                WHERE
                    penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})) t_penerimaan_kb ON t_penerimaan_kb.penerimaan_id2 = t_penerimaan_j.penerimaan_id1
                LEFT JOIN (SELECT 
                    penerimaan_kas_bank.penerimaan_id penerimaan_id3,
                        COUNT(*) count
                FROM
                    penerimaan_kas_bank
                WHERE
                    penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
                GROUP BY penerimaan_id) t_count ON t_count.penerimaan_id3 = t_penerimaan_j.penerimaan_id1
                LEFT JOIN (SELECT 
                    penerimaan_id penerimaan_id4, SUM(jumlah) jumlah
                FROM
                    penerimaan_kas_bank
                WHERE
                    penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
                        AND kas_bank_id != 1
                GROUP BY penerimaan_id) t_selain_kas ON t_selain_kas.penerimaan_id4 = t_penerimaan_j.penerimaan_id1
                GROUP BY t_penerimaan_j.penjualan_nomor , t_penerimaan_kb.kas_bank_id) t_penjualan
                    JOIN
                kas_bank ON kas_bank.id = t_penjualan.kas_bank_id
                    JOIN
                profil ON profil.id = t_penjualan.profil_id 
            UNION SELECT 
                kas_bank_id,
                profil_id,
                penjualan_nomor nomor,
                kas_bank.nama nama_akun,
                profil.nama,
                jumlah
            FROM
                (SELECT DISTINCT
                    penjualan_id,
                        penjualan_nomor,
                        profil_id,
                        IFNULL(kas_bank_id, 1) kas_bank_id,
                        CASE
                            WHEN kas_bank_id = 1 OR kas_bank_id IS NULL THEN SUM(jumlah_pengeluaran - IFNULL(t_selain_kas.jumlah, 0))
                            ELSE SUM(jumlah_pembayaran)
                        END jumlah
                FROM
                    (SELECT 
                    pengeluaran_detail.pengeluaran_id pengeluaran_id1,
                        pengeluaran_detail.jumlah jumlah_pengeluaran,
                        penjualan.id penjualan_id,
                        penjualan.nomor penjualan_nomor,
                        penjualan.profil_id
                FROM
                    penjualan
                JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
                JOIN pengeluaran_detail ON pengeluaran_detail.hutang_piutang_id = hutang_piutang.id
                WHERE
                    pengeluaran_id IN ({$listPengeluaran})) AS t_pengeluaran_j
                LEFT JOIN (SELECT 
                    pengeluaran_id pengeluaran_id2,
                        kas_bank_id,
                        jumlah jumlah_pembayaran
                FROM
                    pengeluaran_kas_bank
                WHERE
                    pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})) t_pengeluaran_kb ON t_pengeluaran_kb.pengeluaran_id2 = t_pengeluaran_j.pengeluaran_id1
                LEFT JOIN (SELECT 
                    pengeluaran_kas_bank.pengeluaran_id pengeluaran_id3,
                        COUNT(*) count
                FROM
                    pengeluaran_kas_bank
                WHERE
                    pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
                GROUP BY pengeluaran_id) t_count ON t_count.pengeluaran_id3 = t_pengeluaran_j.pengeluaran_id1
                LEFT JOIN (SELECT 
                    pengeluaran_id pengeluaran_id4, SUM(jumlah) jumlah
                FROM
                    pengeluaran_kas_bank
                WHERE
                    pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
                        AND kas_bank_id != 1
                GROUP BY pengeluaran_id) t_selain_kas ON t_selain_kas.pengeluaran_id4 = t_pengeluaran_j.pengeluaran_id1
                GROUP BY t_pengeluaran_j.penjualan_nomor , t_pengeluaran_kb.kas_bank_id) t_penjualan
                    JOIN
                kas_bank ON kas_bank.id = t_penjualan.kas_bank_id
                    JOIN
                profil ON profil.id = t_penjualan.profil_id
            ORDER BY nama_akun, nomor            
                ";

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select nama_akun, nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama_akun, nama
                    order by nama_akun, nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
            ':itemPenjualan'     => ItemKeuangan::ITEM_PENJUALAN
        ]);

        return $command->queryAll();
    }

    /**
     * Sub Total per Akun dari Penjualan tunai yang terjadi pada tanggal tsb
     * @return array nama akun, jumlah dari penjualan tunai
     */
    public function totalPenjualanTunaiPerAkun()
    {
        /*
        $sql = "
         SELECT sum(jumlah) jumlah, kb.nama nama_akun
         FROM
         (
            SELECT penjualan.id, p.kas_bank_id, d.jumlah
            FROM penerimaan_detail d
            JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status=:statusPenerimaan AND p.tanggal=:tanggal
            JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id AND hp.asal=:asalHutangPiutang
            JOIN penjualan ON hp.id = penjualan.hutang_piutang_id AND penjualan.tanggal >= :tanggalAwal AND penjualan.tanggal < :tanggalAkhir
            UNION
            SELECT penjualan.id, p.kas_bank_id, d.jumlah
            FROM pengeluaran_detail d
            JOIN pengeluaran p ON d.pengeluaran_id = p.id AND p.status=:statusPengeluaran AND p.tanggal=:tanggal
            JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id AND hp.asal=:asalHutangPiutang
            JOIN penjualan ON hp.id = penjualan.hutang_piutang_id AND penjualan.tanggal >= :tanggalAwal AND penjualan.tanggal < :tanggalAkhir
         ) t
         JOIN penjualan ON t.id = penjualan.id
         JOIN profil ON penjualan.profil_id = profil.id
         JOIN kas_bank kb ON kb.id = t.kas_bank_id
         GROUP BY kb.nama
         ORDER BY kb.nama";
         *
         */

        $listPenerimaan = '
                    SELECT DISTINCT
                        p.id
                    FROM
                        penerimaan_detail d
                    JOIN penerimaan p ON d.penerimaan_id = p.id AND p.status = :statusPenerimaan
                        AND p.tanggal = :tanggal
                    JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :asalHutangPiutang
                    JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :tanggalAwal
                        AND penjualan.tanggal < :tanggalAkhir            
                ';
        $listPengeluaran = '
                    SELECT DISTINCT
                        p.id
                    FROM
                        pengeluaran_detail d
                    JOIN pengeluaran p ON d.pengeluaran_id = p.id AND p.status = :statusPengeluaran
                        AND p.tanggal = :tanggal
                    JOIN hutang_piutang hp ON d.hutang_piutang_id = hp.id
                        AND hp.asal = :asalHutangPiutang
                    JOIN penjualan ON hp.id = penjualan.hutang_piutang_id
                        AND penjualan.tanggal >= :tanggalAwal
                        AND penjualan.tanggal < :tanggalAkhir
            ';

        $sqlPembayaranPenjualan = "
        SELECT 
            tabel_detail.*,
            profil.nama nama,
            kas_bank.nama nama_akun,
            CASE
                WHEN uang_dibayar > 0 THEN uang_dibayar_perakun - (uang_dibayar - penjualan)
                WHEN count IS NULL OR count = 1 THEN penjualan
                ELSE uang_dibayar_perakun
            END jumlah
        FROM
            (SELECT 
                t.*,
                    CASE
                        WHEN
                            t.kb = 1 OR t.count IS NULL
                                OR t.count = 1
                        THEN
                            tp.jumlah
                    END penjualan,
                    CASE t.kb
                        WHEN 1 THEN penerimaan.uang_dibayar
                    END uang_dibayar,
                    t_penjualan.nomor
            FROM
                (SELECT 
                tr.id,
                    tr.nomor nomor_bayar,
                    tr.profil_id,
                    CASE
                        WHEN kb2 > 0 THEN kb2
                        ELSE kb1
                    END kb,
                    CASE
                        WHEN tr.jumlah > 0 THEN tr.jumlah
                        ELSE uang_dibayar
                    END uang_dibayar_perakun,
                    tr.count
            FROM
                (SELECT 
                p.id,
                    p.nomor,
                    p.profil_id,
                    p.kas_bank_id kb1,
                    p.uang_dibayar,
                    pkb.kas_bank_id kb2,
                    pkb.jumlah,
                    tc.count
            FROM
                penerimaan p
            LEFT JOIN penerimaan_kas_bank pkb ON p.id = pkb.penerimaan_id
            LEFT JOIN (SELECT 
                penerimaan_kas_bank.penerimaan_id, COUNT(*) count
            FROM
                penerimaan_kas_bank
            WHERE
                penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
            GROUP BY penerimaan_id) tc ON tc.penerimaan_id = p.id
            WHERE
                p.id IN ({$listPenerimaan})
            ORDER BY p.nomor) tr) t
            JOIN (SELECT 
                penerimaan_id, SUM(jumlah) jumlah
            FROM
                penerimaan_detail
            WHERE
                penerimaan_detail.item_id = :itemPenjualan 
                AND penerimaan_id IN ({$listPenerimaan})
            GROUP BY penerimaan_id) tp ON tp.penerimaan_id = t.id
            JOIN penerimaan ON t.id = penerimaan.id
            JOIN (SELECT 
                penerimaan_detail.penerimaan_id, penjualan.nomor
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN penerimaan_detail ON penerimaan_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                penerimaan_id IN ({$listPenerimaan})) t_penjualan ON t_penjualan.penerimaan_id = t.id UNION SELECT 
                t.*,
                    CASE
                        WHEN
                            t.kb = 1 OR t.count IS NULL
                                OR t.count = 1
                        THEN
                            tp.jumlah
                    END penjualan,
                    CASE t.kb
                        WHEN 1 THEN pengeluaran.uang_dibayar
                    END uang_dibayar,
                    t_penjualan.nomor
            FROM
                (SELECT 
                tr.id,
                    tr.nomor nomor_bayar,
                    tr.profil_id,
                    CASE
                        WHEN kb2 > 0 THEN kb2
                        ELSE kb1
                    END kb,
                    CASE
                        WHEN tr.jumlah > 0 THEN tr.jumlah
                        ELSE uang_dibayar
                    END uang_dibayar_perakun,
                    tr.count
            FROM
                (SELECT 
                p.id,
                    p.nomor,
                    p.profil_id,
                    p.kas_bank_id kb1,
                    p.uang_dibayar,
                    pkb.kas_bank_id kb2,
                    pkb.jumlah,
                    tc.count
            FROM
                pengeluaran p
            LEFT JOIN pengeluaran_kas_bank pkb ON p.id = pkb.pengeluaran_id
            LEFT JOIN (SELECT 
                pengeluaran_kas_bank.pengeluaran_id, COUNT(*) count
            FROM
                pengeluaran_kas_bank
            WHERE
                pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
            GROUP BY pengeluaran_id) tc ON tc.pengeluaran_id = p.id
            WHERE
                p.id IN ({$listPengeluaran})
            ORDER BY p.nomor) tr) t
            JOIN (SELECT
                pengeluaran_id, SUM(jumlah) jumlah
            FROM
                pengeluaran_detail
            WHERE
                pengeluaran_detail.item_id = :itemPenjualan 
                AND pengeluaran_id IN ({$listPengeluaran})
            GROUP BY pengeluaran_id) tp ON tp.pengeluaran_id = t.id
            JOIN pengeluaran ON t.id = pengeluaran.id
            JOIN (SELECT 
                pengeluaran_detail.pengeluaran_id, penjualan.nomor
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN pengeluaran_detail ON pengeluaran_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                pengeluaran_id IN ({$listPengeluaran})) t_penjualan ON t_penjualan.pengeluaran_id = t.id) tabel_detail
                JOIN
            profil ON profil.id = tabel_detail.profil_id
                JOIN
            kas_bank ON kas_bank.id = tabel_detail.kb
        ORDER BY kas_bank.nama , tabel_detail.nomor
            ";

        $sqlPembayaranPenjualan = "
        SELECT 
            kas_bank_id,
            profil_id,
            penjualan_nomor nomor,
            kas_bank.nama nama_akun,
            profil.nama,
            jumlah
        FROM
            (SELECT DISTINCT
                penjualan_id,
                    penjualan_nomor,
                    profil_id,
                    IFNULL(kas_bank_id, 1) kas_bank_id,
                    CASE
                        WHEN kas_bank_id = 1 OR kas_bank_id IS NULL THEN SUM(jumlah_penerimaan - IFNULL(t_selain_kas.jumlah, 0))
                        ELSE SUM(jumlah_pembayaran)
                    END jumlah
            FROM
                (SELECT 
                penerimaan_detail.penerimaan_id penerimaan_id1,
                    penerimaan_detail.jumlah jumlah_penerimaan,
                    penjualan.id penjualan_id,
                    penjualan.nomor penjualan_nomor,
                    penjualan.profil_id
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN penerimaan_detail ON penerimaan_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                penerimaan_id IN ({$listPenerimaan})) AS t_penerimaan_j
            LEFT JOIN (SELECT 
                penerimaan_id penerimaan_id2,
                    kas_bank_id,
                    jumlah jumlah_pembayaran
            FROM
                penerimaan_kas_bank
            WHERE
                penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})) t_penerimaan_kb ON t_penerimaan_kb.penerimaan_id2 = t_penerimaan_j.penerimaan_id1
            LEFT JOIN (SELECT 
                penerimaan_kas_bank.penerimaan_id penerimaan_id3,
                    COUNT(*) count
            FROM
                penerimaan_kas_bank
            WHERE
                penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
            GROUP BY penerimaan_id) t_count ON t_count.penerimaan_id3 = t_penerimaan_j.penerimaan_id1
            LEFT JOIN (SELECT 
                penerimaan_id penerimaan_id4, SUM(jumlah) jumlah
            FROM
                penerimaan_kas_bank
            WHERE
                penerimaan_kas_bank.penerimaan_id IN ({$listPenerimaan})
                    AND kas_bank_id != 1
            GROUP BY penerimaan_id) t_selain_kas ON t_selain_kas.penerimaan_id4 = t_penerimaan_j.penerimaan_id1
            GROUP BY t_penerimaan_j.penjualan_nomor , t_penerimaan_kb.kas_bank_id) t_penjualan
                JOIN
            kas_bank ON kas_bank.id = t_penjualan.kas_bank_id
                JOIN
            profil ON profil.id = t_penjualan.profil_id 
        UNION SELECT 
            kas_bank_id,
            profil_id,
            penjualan_nomor nomor,
            kas_bank.nama nama_akun,
            profil.nama,
            jumlah
        FROM
            (SELECT DISTINCT
                penjualan_id,
                    penjualan_nomor,
                    profil_id,
                    IFNULL(kas_bank_id, 1) kas_bank_id,
                    CASE
                        WHEN kas_bank_id = 1 OR kas_bank_id IS NULL THEN SUM(jumlah_pengeluaran - IFNULL(t_selain_kas.jumlah, 0))
                        ELSE SUM(jumlah_pembayaran)
                    END jumlah
            FROM
                (SELECT 
                pengeluaran_detail.pengeluaran_id pengeluaran_id1,
                    pengeluaran_detail.jumlah jumlah_pengeluaran,
                    penjualan.id penjualan_id,
                    penjualan.nomor penjualan_nomor,
                    penjualan.profil_id
            FROM
                penjualan
            JOIN hutang_piutang ON hutang_piutang.id = penjualan.hutang_piutang_id
            JOIN pengeluaran_detail ON pengeluaran_detail.hutang_piutang_id = hutang_piutang.id
            WHERE
                pengeluaran_id IN ({$listPengeluaran})) AS t_pengeluaran_j
            LEFT JOIN (SELECT 
                pengeluaran_id pengeluaran_id2,
                    kas_bank_id,
                    jumlah jumlah_pembayaran
            FROM
                pengeluaran_kas_bank
            WHERE
                pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})) t_pengeluaran_kb ON t_pengeluaran_kb.pengeluaran_id2 = t_pengeluaran_j.pengeluaran_id1
            LEFT JOIN (SELECT 
                pengeluaran_kas_bank.pengeluaran_id pengeluaran_id3,
                    COUNT(*) count
            FROM
                pengeluaran_kas_bank
            WHERE
                pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
            GROUP BY pengeluaran_id) t_count ON t_count.pengeluaran_id3 = t_pengeluaran_j.pengeluaran_id1
            LEFT JOIN (SELECT 
                pengeluaran_id pengeluaran_id4, SUM(jumlah) jumlah
            FROM
                pengeluaran_kas_bank
            WHERE
                pengeluaran_kas_bank.pengeluaran_id IN ({$listPengeluaran})
                    AND kas_bank_id != 1
            GROUP BY pengeluaran_id) t_selain_kas ON t_selain_kas.pengeluaran_id4 = t_pengeluaran_j.pengeluaran_id1
            GROUP BY t_pengeluaran_j.penjualan_nomor , t_pengeluaran_kb.kas_bank_id) t_penjualan
                JOIN
            kas_bank ON kas_bank.id = t_penjualan.kas_bank_id
                JOIN
            profil ON profil.id = t_penjualan.profil_id
        ORDER BY nama_akun, nomor       
            ";

        $sql = "
        SELECT 
            nama_akun, SUM(jumlah) jumlah
        FROM
            ({$sqlPembayaranPenjualan}) t_detail_bayar
        GROUP BY nama_akun
            ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
            ':itemPenjualan'     => ItemKeuangan::ITEM_PENJUALAN
        ]);

        return $command->queryAll();
    }

    /**
     * Total Penjualan Tunai pada tanggal tsb
     * @return int Total penjualan tunai
     */
    public function totalPenjualanTunai()
    {
        $command = Yii::app()->db->createCommand('
         select sum(jumlah) total
         FROM
         (
            select penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
            union
            select penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
         ) t
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $penjualanTunai = $command->queryRow();
        return $penjualanTunai['total'];
    }

    /**
     * Penjualan yang belum dibayar / belum lunas pada tanggal $tanggal
     * @return array penjualan_id, nomor (penjualan), nama (profil), jumlah (penjualan), jml_bayar (tunai)
     */
    public function penjualanPiutang()
    {
        $sql = '
         select penjualan.nomor, profil.nama, t3.jumlah, t3.jml_bayar
         from
         (
            select pj.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from penjualan pj
            join hutang_piutang hp on pj.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where pj.tanggal >= :tanggalAwal and pj.tanggal < :tanggalAkhir
            group by pj.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3
         join penjualan on t3.id=penjualan.id
         join profil on penjualan.profil_id=profil.id
         order by penjualan.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah, sum(jml_bayar) jml_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalPenjualanPiutang()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t3.jumlah-t3.jml_bayar) total
         from
         (
            select pj.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from penjualan pj
            join hutang_piutang hp on pj.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where pj.tanggal >= :tanggalAwal and pj.tanggal < :tanggalAkhir
            group by pj.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        $penjualanPiutang = $command->queryRow();
        return $penjualanPiutang['total'];
    }

    /**
     * Pembayaran penjualan, baik lewat penerimaan maupun pengeluaran, untuk penjualan yang sudah lewat (sebelum tanggal $tanggal)
     * @return array nomor (penjualan), nama (profil), jumlah_bayar (jumlah pembayaran)
     */
    public function penjualanBayar()
    {
        $sql = '
         select profil.nama, penjualan.nomor, t1.*
         from
         (
             select penjualan.id, sum(t.jumlah_bayar) jumlah_bayar
             from
             (
                select sum(pd.jumlah) jumlah_bayar, penjualan.id
                from penerimaan_detail pd
                join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
                join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
                join penjualan on hp.id=penjualan.hutang_piutang_id and penjualan.tanggal < :tanggalAwal
                group by penjualan.id
                union
                select sum(pd.jumlah) jumlah_bayar, penjualan.id
                from pengeluaran_detail pd
                join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
                join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
                join penjualan on hp.id=penjualan.hutang_piutang_id and penjualan.tanggal < :tanggalAwal
                group by penjualan.id
             ) t
             join penjualan on t.id=penjualan.id
             group by penjualan.id
        ) t1
        join penjualan on t1.id = penjualan.id
        join profil on penjualan.profil_id = profil.id';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalPenjualanBayar()
    {
        $command = Yii::app()->db->createCommand('
         select sum(jumlah_bayar) total
         from
         (
            select sum(pd.jumlah) jumlah_bayar, penjualan.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id=penjualan.hutang_piutang_id and penjualan.tanggal < :tanggalAwal
            group by penjualan.id
            union
            select sum(pd.jumlah) jumlah_bayar, penjualan.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id=penjualan.hutang_piutang_id and penjualan.tanggal < :tanggalAwal
            group by penjualan.id
         ) t
         join penjualan on t.id=penjualan.id
         join profil on penjualan.profil_id=profil.id');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        $penjualanBayar = $command->queryRow();
        return $penjualanBayar['total'];
    }

    public function marginPenjualanTunai()
    {
        $sql = '
         select penjualan.nomor, profil.nama, jumlah_bayar, harga_beli, harga_jual, ((harga_jual - harga_beli)/harga_jual) * jumlah_bayar margin
         from
         (
            select t1.id, sum(jumlah) jumlah_bayar
            from
            (
               select penjualan.id, d.jumlah
               from penerimaan_detail d
               join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               union
               select penjualan.id, d.jumlah
               from pengeluaran_detail d
               join pengeluaran p on d.pengeluaran_id = p.id and p.status=1 and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
            ) t1
            group by t1.id
         ) t_bayar
         join
         (
            select id, sum(harga_jual) harga_jual, sum(harga_beli) harga_beli
            from(
               select penjualan.id, sum(jual_detail.harga_jual * hpp.qty) harga_jual,
               sum(hpp.harga_beli * hpp.qty) harga_beli
               from penerimaan_detail d
               join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
               join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
               group by penjualan.id
               union
               select penjualan.id, sum(jual_detail.harga_jual * hpp.qty) harga_jual,
               sum(hpp.harga_beli * hpp.qty) harga_beli
               from pengeluaran_detail d
               join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
               join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
               group by penjualan.id
            ) t2 group by id
         ) t_harga on t_bayar.id=t_harga.id
         join penjualan on t_bayar.id=penjualan.id
         join profil on penjualan.profil_id=profil.id';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(margin) margin
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalMarginPenjualanTunai()
    {
        $command = Yii::app()->db->createCommand('
         select sum(((harga_jual - harga_beli)/harga_jual) * jumlah_bayar) total
         from
         (
            select t1.id, sum(jumlah) jumlah_bayar
            from
            (
               select penjualan.id, d.jumlah
               from penerimaan_detail d
               join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               union
               select penjualan.id, d.jumlah
               from pengeluaran_detail d
               join pengeluaran p on d.pengeluaran_id = p.id and p.status=1 and p.tanggal=:tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
            ) t1
            group by t1.id
         ) t_bayar
         join
         (
            select id, sum(harga_jual) harga_jual, sum(harga_beli) harga_beli
            from(
               select penjualan.id, sum(jual_detail.harga_jual * hpp.qty) harga_jual,
               sum(hpp.harga_beli * hpp.qty) harga_beli
               from penerimaan_detail d
               join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
               join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
               group by penjualan.id
               union
               select penjualan.id, sum(jual_detail.harga_jual * hpp.qty) harga_jual,
               sum(hpp.harga_beli * hpp.qty) harga_beli
               from pengeluaran_detail d
               join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
               join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
               join penjualan on hp.id = penjualan.hutang_piutang_id and penjualan.tanggal >= :tanggalAwal and penjualan.tanggal < :tanggalAkhir
               join penjualan_detail jual_detail on penjualan.id = jual_detail.penjualan_id
               join harga_pokok_penjualan hpp on jual_detail.id=hpp.penjualan_detail_id
               group by penjualan.id
            ) t2 group by id
         ) t_harga on t_bayar.id=t_harga.id');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        $margin = $command->queryRow();
        return $margin['total'];
    }

    public function totalMarginPenjualan()
    {
        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('*, (t_penjualan.total - t_modal.totalModal) margin');
        $commandRekap->from('(SELECT SUM(pd.harga_jual * pd.qty) total
                        FROM
                            penjualan_detail pd
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND pj.tanggal >= :tanggalAwal and pj.tanggal < :tanggalAkhir
                        ) t_penjualan,
                        (SELECT SUM(hpp.qty * hpp.harga_beli) totalmodal
                        FROM
                            harga_pokok_penjualan hpp
                        JOIN penjualan_detail pd ON hpp.penjualan_detail_id = pd.id
                        JOIN penjualan pj ON pd.penjualan_id = pj.id AND pj.status!=:statusDraft
                            AND pj.tanggal >= :tanggalAwal and pj.tanggal < :tanggalAkhir
                        ) t_modal');

        $commandRekap->bindValue(':statusDraft', Penjualan::STATUS_DRAFT);
        $commandRekap->bindValue(':tanggal', $this->tanggal);
        $commandRekap->bindValue(':tanggalAwal', $this->tanggalAwal);
        $commandRekap->bindValue(':tanggalAkhir', $this->tanggalAkhir);

        $rekap = $commandRekap->queryRow();
        return $rekap['margin'];
    }

    public function itemPengeluaran()
    {
        $itemKhusus        = [ItemKeuangan::POS_INFAQ, ItemKeuangan::POS_DISKON_PER_NOTA, ItemKeuangan::POS_TARIK_TUNAI_PENGELUARAN];
        $condForItemKhusus = 'item.id in (';
        $f                 = true;
        foreach ($itemKhusus as $value) {
            if (!$f) {
                $condForItemKhusus .= ',';
            }
            $condForItemKhusus .= $value;
            $f                 = false;
        }
        $condForItemKhusus .= ')';

        $parents = ItemKeuangan::model()->findAll('parent_id is null');
        $itemArr = [];

        $sql = "
         select profil.nama, item.nama akun, pd.keterangan, pd.jumlah
         from penerimaan_detail pd
         join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPenerimaan
         union all
         select profil.nama, item.nama, pd.keterangan, pd.jumlah
         from pengeluaran_detail pd
         join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPengeluaran";

        if ($this->groupByProfil['keu']) {
            $sql = "
                    select nama, akun, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama, akun
                    order by nama, akun
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $commandTotal = Yii::app()->db->createCommand("
         select sum(jumlah) total
         from
         (
            select sum(pd.jumlah) jumlah
            from penerimaan_detail pd
            join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPenerimaan
            union all
            select sum(pd.jumlah) jumlah
            from pengeluaran_detail pd
            join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPengeluaran
         ) t");

        foreach ($parents as $parent) {
            $command->bindValues([
                ':tanggal'           => $this->tanggal,
                ':itemTrx'           => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId'          => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_DEBET,
                ':posisiPenerimaan'  => PenerimaanDetail::POSISI_KREDIT,
            ]);
            $commandTotal->bindValues([
                ':tanggal'           => $this->tanggal,
                ':itemTrx'           => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId'          => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_DEBET,
                ':posisiPenerimaan'  => PenerimaanDetail::POSISI_KREDIT,
            ]);
            $jumlah    = $commandTotal->queryRow();
            $itemArr[] = [
                'id'    => $parent->id,
                'nama'  => $parent->nama,
                'total' => $jumlah['total'],
                'items' => $command->queryAll()
            ];
        }
        return $itemArr;
    }

    public function itemPenerimaan()
    {
        $itemKhusus        = [ItemKeuangan::POS_INFAQ, ItemKeuangan::POS_DISKON_PER_NOTA, ItemKeuangan::POS_TARIK_TUNAI_PENGELUARAN];
        $condForItemKhusus = 'item.id in (';
        $f                 = true;
        foreach ($itemKhusus as $value) {
            if (!$f) {
                $condForItemKhusus .= ',';
            }
            $condForItemKhusus .= $value;
            $f                 = false;
        }
        $condForItemKhusus .= ')';
        // echo $condForItemKhusus;        Yii::app()->end();

        $parents = ItemKeuangan::model()->findAll('parent_id is null');
        $itemArr = [];

        $sql = "
         select profil.nama, item.nama akun, pd.keterangan, pd.jumlah
         from penerimaan_detail pd
         join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPenerimaan
         union all
         select profil.nama, item.nama, pd.keterangan, pd.jumlah
         from pengeluaran_detail pd
         join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPengeluaran";

        if ($this->groupByProfil['keu']) {
            $sql = "
                    select nama, akun, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama, akun
                    order by nama, akun
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $commandTotal = Yii::app()->db->createCommand("
         select sum(jumlah) total
         from
         (
            select sum(pd.jumlah) jumlah
            from penerimaan_detail pd
            join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPenerimaan
            union all
            select sum(pd.jumlah) jumlah
            from pengeluaran_detail pd
            join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and (item.id > :itemTrx or {$condForItemKhusus}) and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPengeluaran
         ) t");

        foreach ($parents as $parent) {
            $command->bindValues([
                ':tanggal'           => $this->tanggal,
                ':itemTrx'           => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId'          => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_KREDIT,
                ':posisiPenerimaan'  => PenerimaanDetail::POSISI_DEBET,
            ]);
            $commandTotal->bindValues([
                ':tanggal'           => $this->tanggal,
                ':itemTrx'           => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId'          => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_KREDIT,
                ':posisiPenerimaan'  => PenerimaanDetail::POSISI_DEBET,
            ]);
            $jumlah    = $commandTotal->queryRow();
            $itemArr[] = [
                'id'    => $parent->id,
                'nama'  => $parent->nama,
                'total' => $jumlah['total'],
                'items' => $command->queryAll()
            ];
        }
        return $itemArr;
    }

    public function returBeliTunai()
    {
        $sql = '
         select retur_pembelian.nomor, sum(jumlah) jumlah, profil.nama
         FROM
         (
            select retur_pembelian.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id = retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal >= :tanggalAwal and retur_pembelian.tanggal < :tanggalAkhir
            union
            select retur_pembelian.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id = retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal >= :tanggalAwal and retur_pembelian.tanggal < :tanggalAkhir
         ) t
         join retur_pembelian on t.id = retur_pembelian.id
         join profil on retur_pembelian.profil_id = profil.id
         group by t.id
         order by retur_pembelian.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalReturBeliTunai()
    {
        $command = Yii::app()->db->createCommand('
         select sum(jumlah) total
         FROM
         (
            select retur_pembelian.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id = retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal >= :tanggalAwal and retur_pembelian.tanggal < :tanggalAkhir
            union
            select retur_pembelian.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id = retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal >= :tanggalAwal and retur_pembelian.tanggal < :tanggalAkhir
         ) t
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $returBeliTunai = $command->queryRow();
        return $returBeliTunai['total'];
    }

    public function returBeliPiutang()
    {
        $sql = '
         select rb.nomor, profil.nama, t3.jumlah-t3.jml_bayar jumlah
         from
         (
            select rp.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from retur_pembelian rp
            join hutang_piutang hp on rp.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where rp.tanggal >= :tanggalAwal and rp.tanggal < :tanggalAkhir
            group by rp.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3
         join retur_pembelian rb on t3.id=rb.id
         join profil on rb.profil_id=profil.id
         order by rb.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalReturBeliPiutang()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t3.jumlah-t3.jml_bayar) total
         from
         (
            select rp.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from retur_pembelian rp
            join hutang_piutang hp on rp.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where rp.tanggal >= :tanggalAwal and rp.tanggal < :tanggalAkhir
            group by rp.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $piutangReturBeli = $command->queryRow();
        return $piutangReturBeli['total'];
    }

    public function returBeliBayar()
    {
        $sql = '
         select retur_pembelian.nomor, profil.nama, t.jumlah_bayar
         from
         (
            select sum(pd.jumlah) jumlah_bayar, retur_pembelian.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id=retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal < :tanggalAwal
            group by retur_pembelian.id
            union
            select sum(pd.jumlah) jumlah_bayar, retur_pembelian.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id=retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal < :tanggalAwal
            group by retur_pembelian.id
         ) t
         join retur_pembelian on t.id=retur_pembelian.id
         join profil on retur_pembelian.profil_id=profil.id
         order by retur_pembelian.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalReturBeliBayar()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t.jumlah_bayar) total
         from
         (
            select sum(pd.jumlah) jumlah_bayar, retur_pembelian.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id=retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal < :tanggalAwal
            group by retur_pembelian.id
            union
            select sum(pd.jumlah) jumlah_bayar, retur_pembelian.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_pembelian on hp.id=retur_pembelian.hutang_piutang_id and retur_pembelian.tanggal < :tanggalAwal
            group by retur_pembelian.id
         ) t
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $bayarReturBeli = $command->queryRow();
        return $bayarReturBeli['total'];
    }

    public function returJualTunai()
    {
        $sql = '
         select retur_penjualan.nomor, sum(jumlah) jumlah, profil.nama
         FROM
         (
            select retur_penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal >= :tanggalAwal and retur_penjualan.tanggal < :tanggalAkhir
            union
            select retur_penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal >= :tanggalAwal and retur_penjualan.tanggal < :tanggalAkhir
         ) t
         join retur_penjualan on t.id = retur_penjualan.id
         join profil on retur_penjualan.profil_id = profil.id
         group by t.id
         order by retur_penjualan.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalReturJualTunai()
    {
        $command = Yii::app()->db->createCommand('
         select sum(jumlah) total
         FROM
         (
            select retur_penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal >= :tanggalAwal and retur_penjualan.tanggal < :tanggalAkhir
            union
            select retur_penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id = retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal >= :tanggalAwal and retur_penjualan.tanggal < :tanggalAkhir
         ) t
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $returJualTunai = $command->queryRow();
        return $returJualTunai['total'];
    }

    public function returJualHutang()
    {
        $sql = '
         select rb.nomor, profil.nama, t3.jumlah-t3.jml_bayar jumlah
         from
         (
            select rp.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from retur_penjualan rp
            join hutang_piutang hp on rp.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where rp.tanggal >= :tanggalAwal and rp.tanggal < :tanggalAkhir
            group by rp.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3
         join retur_penjualan rb on t3.id=rb.id
         join profil on rb.profil_id=profil.id
         order by rb.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        return $command->queryAll();
    }

    public function totalReturJualHutang()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t3.jumlah-t3.jml_bayar) total
         from
         (
            select rp.id, hp.jumlah, sum(ifnull(t1.jumlah,0)+ifnull(t2.jumlah,0)) jml_bayar
            from retur_penjualan rp
            join hutang_piutang hp on rp.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            left join
            (
               select pd.* from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            ) t1 on hp.id=t1.hutang_piutang_id
            left join
            (
               select pd.* from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            ) t2 on hp.id=t2.hutang_piutang_id
            where rp.tanggal >= :tanggalAwal and rp.tanggal < :tanggalAkhir
            group by rp.id
            having sum(ifnull(t1.jumlah,0)) + sum(ifnull(t2.jumlah,0)) < hp.jumlah
         ) t3');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':tanggalAkhir'      => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);
        $returJualHutang = $command->queryRow();
        return $returJualHutang['total'];
    }

    public function returJualBayar()
    {
        $sql = '
         select retur_penjualan.nomor, profil.nama, t.jumlah_bayar
         from
         (
            select sum(pd.jumlah) jumlah_bayar, retur_penjualan.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id=retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal < :tanggalAwal
            group by retur_penjualan.id
            union
            select sum(pd.jumlah) jumlah_bayar, retur_penjualan.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id=retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal < :tanggalAwal
            group by retur_penjualan.id
         ) t
         join retur_penjualan on t.id=retur_penjualan.id
         join profil on retur_penjualan.profil_id=profil.id
         order by retur_penjualan.nomor';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        return $command->queryAll();
    }

    public function totalReturJualBayar()
    {
        $command = Yii::app()->db->createCommand('
         select sum(t.jumlah_bayar) total
         from
         (
            select sum(pd.jumlah) jumlah_bayar, retur_penjualan.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id=penerimaan.id and penerimaan.status=:statusPenerimaan and penerimaan.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id=retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal < :tanggalAwal
            group by retur_penjualan.id
            union
            select sum(pd.jumlah) jumlah_bayar, retur_penjualan.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id=pengeluaran.id and pengeluaran.status=:statusPengeluaran and pengeluaran.tanggal=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join retur_penjualan on hp.id=retur_penjualan.hutang_piutang_id and retur_penjualan.tanggal < :tanggalAwal
            group by retur_penjualan.id
         ) t
         ');

        $command->bindValues([
            ':tanggal'           => $this->tanggal,
            ':tanggalAwal'       => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan'  => Penerimaan::STATUS_BAYAR
        ]);

        $bayarReturJual = $command->queryRow();
        return $bayarReturJual['total'];
    }

    /**
     * Tarik tunai yang terjadi pada tanggal tsb
     * @return array nama akun, nomor penjualan, nama, jumlah dari tarik tunai
     */
    public function tarikTunai()
    {
        $sql = '
        SELECT 
            kas_bank.nama nama_akun,
            penjualan.nomor,
            profil.nama,
            t.jumlah
        FROM
            penjualan_tarik_tunai t
                JOIN
            penjualan ON penjualan.id = t.penjualan_id
                JOIN
            profil ON profil.id = penjualan.profil_id
                JOIN
            kas_bank ON kas_bank.id = t.kas_bank_id
        WHERE
            t.updated_at >= :tanggalAwal
                AND t.updated_at < :tanggalAkhir
        ORDER BY kas_bank.nama , penjualan.nomor            
            ';

        if ($this->groupByProfil['inv']) {
            $sql = "
                    select nama_akun, nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama_akun, nama
                    order by nama_akun, nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggalAwal'  => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir
        ]);

        return $command->queryAll();
    }

    public function totalTarikTunaiPerAkun()
    {
        $sql = '
        SELECT 
            kas_bank.nama nama_akun,
            penjualan.nomor,
            profil.nama,
            t.jumlah
        FROM
            penjualan_tarik_tunai t
                JOIN
            penjualan ON penjualan.id = t.penjualan_id
                JOIN
            profil ON profil.id = penjualan.profil_id
                JOIN
            kas_bank ON kas_bank.id = t.kas_bank_id
        WHERE
            t.updated_at >= :tanggalAwal
                AND t.updated_at < :tanggalAkhir
        ORDER BY kas_bank.nama , penjualan.nomor            
            ';

        $sql = "
        SELECT 
            nama_akun, SUM(jumlah) jumlah
        FROM
            ({$sql}) t
        GROUP BY nama_akun
        ORDER BY nama_akun
            ";

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggalAwal'  => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir
        ]);

        return $command->queryAll();
    }

    public function totalTarikTunai()
    {
        $sql = '
        SELECT 
            sum(t.jumlah) total
        FROM
            penjualan_tarik_tunai t
        WHERE
            t.updated_at >= :tanggalAwal
                AND t.updated_at < :tanggalAkhir           
            ';

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues([
            ':tanggalAwal'  => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir
        ]);

        return $command->queryRow()['total'];
    }
}
