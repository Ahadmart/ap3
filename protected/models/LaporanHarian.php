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
    public $groupByProfil = false;

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
        return array(
            array('tanggal', 'required'),
            array('tanggal', 'unique'),
            array('saldo_akhir', 'length', 'max' => 18),
            array('keterangan', 'length', 'max' => 5000),
            array('updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, tanggal, saldo_akhir, keterangan, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'tanggal' => 'Tanggal',
            'saldo_akhir' => 'Saldo Akhir Asli',
            'keterangan' => 'Remarks',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
        );
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

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        $this->tanggal = !empty($this->tanggal) ? date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d') : NULL;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d', $this->tanggal), 'd-m-Y') : '0';
        $this->saldo_akhir = number_format($this->saldo_akhir, 0, false, false);
        return parent::afterFind();
    }

    public function saldoAwal()
    {
        $command = Yii::app()->db->createCommand("
         select harian.saldo_akhir
         from laporan_harian harian
         where tanggal=(select tanggal from laporan_harian where tanggal < :tanggal and saldo_akhir is not null order by tanggal desc limit 1)
              ");
        $command->bindValue(':tanggal', $this->tanggal);
        $harian = $command->queryRow();
        return $harian ? $harian['saldo_akhir'] : Config::model()->find("nama='keuangan.saldo_awal'")->nilai;
    }

    public function saldoAkhir()
    {
        $pengeluaran = $this->itemPengeluaran();
        $totalPengeluaran = 0;
        foreach ($pengeluaran as $kategoriPengeluaran) {
            $totalPengeluaran+=$kategoriPengeluaran['total'];
        }

        $penerimaan = $this->itemPenerimaan();
        $totalPenerimaan = 0;
        foreach ($penerimaan as $kategoriPenerimaan) {
            $totalPenerimaan+=$kategoriPenerimaan['total'];
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
        $sql = "
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
         order by pembelian.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);


        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalPembelianTunai()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $pembelian = $command->queryRow();
        return $pembelian['total'];
    }

    /**
     * Pembelian yang masih hutang
     * @return array Pembelian pada tanggal tsb yang masih hutang per trx (nomor pembelian, profil, total)
     */
    public function pembelianHutang()
    {
        $sql = "
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
         order by pembelian.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);


        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalPembelianHutang()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t3");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $hutangPembelian = $command->queryRow();
        return $hutangPembelian['total'];
    }

    /**
     * Pembelian yang dibayar pada tanggal tsb, per nomor pembelian
     * @return array nomor pembelian, nama profil, tanggal pembelian, total pembayaran
     */
    public function pembelianBayar()
    {
        $sql = "
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
         order by pembelian.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(total_bayar) total_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        return $command->queryAll();
    }

    public function totalPembelianBayar()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t1");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $bayarPembelian = $command->queryRow();
        return $bayarPembelian['total'];
    }

    /**
     * Penjualan tunai yang terjadi pada tanggal tsb
     * @return array nomor, nama, jumlah dari penjualan tunai
     */
    public function penjualanTunai()
    {
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

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
        ));

        return $command->queryAll();
    }

    /**
     * Total Penjualan Tunai pada tanggal tsb
     * @return text Total penjualan tunai
     */
    public function totalPenjualanTunai()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $penjualanTunai = $command->queryRow();
        return $penjualanTunai['total'];
    }

    /**
     * Penjualan yang belum dibayar / belum lunas pada tanggal $tanggal
     * @return array penjualan_id, nomor (penjualan), nama (profil), jumlah (penjualan), jml_bayar (tunai)
     */
    public function penjualanPiutang()
    {
        $sql = "
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
         order by penjualan.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah, sum(jml_bayar) jml_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }
        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalPenjualanPiutang()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t3");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        $penjualanPiutang = $command->queryRow();
        return $penjualanPiutang['total'];
    }

    /**
     * Pembayaran penjualan, baik lewat penerimaan maupun pengeluaran, untuk penjualan yang sudah lewat (sebelum tanggal $tanggal)
     * @return array nomor (penjualan), nama (profil), jumlah_bayar (jumlah pembayaran)
     */
    public function penjualanBayar()
    {
        $sql = "
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
        join profil on penjualan.profil_id = profil.id";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalPenjualanBayar()
    {
        $command = Yii::app()->db->createCommand("
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
         join profil on penjualan.profil_id=profil.id");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        $penjualanBayar = $command->queryRow();
        return $penjualanBayar['total'];
    }

    public function marginPenjualanTunai()
    {
        $sql = "
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
         join profil on penjualan.profil_id=profil.id";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(margin) margin
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalMarginPenjualanTunai()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t_harga on t_bayar.id=t_harga.id");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        $margin = $command->queryRow();
        return $margin['total'];
    }

    public function totalMarginPenjualan()
    {
        $commandRekap = Yii::app()->db->createCommand();
        $commandRekap->select('*, (t_penjualan.total - t_modal.totalModal) margin');
        $commandRekap->from("(SELECT SUM(pd.harga_jual * pd.qty) total
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
                        ) t_modal");

        $commandRekap->bindValue(":statusDraft", Penjualan::STATUS_DRAFT);
        $commandRekap->bindValue(":tanggal", $this->tanggal);
        $commandRekap->bindValue(":tanggalAwal", $this->tanggalAwal);
        $commandRekap->bindValue(":tanggalAkhir", $this->tanggalAkhir);

        $rekap = $commandRekap->queryRow();
        return $rekap['margin'];
    }

    public function itemPengeluaran()
    {
        $parents = ItemKeuangan::model()->findAll('parent_id is null');
        $itemArr = array();

        $command = Yii::app()->db->createCommand("
         select profil.nama, item.nama akun, pd.keterangan, pd.jumlah
         from penerimaan_detail pd
         join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPenerimaan
         union all
         select profil.nama, item.nama, pd.keterangan, pd.jumlah
         from pengeluaran_detail pd
         join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPengeluaran");

        $commandTotal = Yii::app()->db->createCommand("
         select sum(jumlah) total
         from
         (
            select sum(pd.jumlah) jumlah
            from penerimaan_detail pd
            join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPenerimaan
            union all
            select sum(pd.jumlah) jumlah
            from pengeluaran_detail pd
            join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPengeluaran
         ) t");

        foreach ($parents as $parent) {

            $command->bindValues(array(
                ':tanggal' => $this->tanggal,
                ':itemTrx' => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId' => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_DEBET,
                ':posisiPenerimaan' => PenerimaanDetail::POSISI_KREDIT,
            ));
            $commandTotal->bindValues(array(
                ':tanggal' => $this->tanggal,
                ':itemTrx' => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId' => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_DEBET,
                ':posisiPenerimaan' => PenerimaanDetail::POSISI_KREDIT,
            ));
            $jumlah = $commandTotal->queryRow();
            $itemArr[] = array(
                'id' => $parent->id,
                'nama' => $parent->nama,
                'total' => $jumlah['total'],
                'items' => $command->queryAll()
            );
        }
        return $itemArr;
    }

    public function itemPenerimaan()
    {
        $parents = ItemKeuangan::model()->findAll('parent_id is null');
        $itemArr = array();

        $command = Yii::app()->db->createCommand("
         select profil.nama, item.nama akun, pd.keterangan, pd.jumlah
         from penerimaan_detail pd
         join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPenerimaan
         union all
         select profil.nama, item.nama, pd.keterangan, pd.jumlah
         from pengeluaran_detail pd
         join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
         join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
         join profil on p.profil_id=profil.id
         where pd.posisi=:posisiPengeluaran");

        $commandTotal = Yii::app()->db->createCommand("
         select sum(jumlah) total
         from
         (
            select sum(pd.jumlah) jumlah
            from penerimaan_detail pd
            join penerimaan p on pd.penerimaan_id=p.id and p.status=:statusPenerimaan and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPenerimaan
            union all
            select sum(pd.jumlah) jumlah
            from pengeluaran_detail pd
            join pengeluaran p on pd.pengeluaran_id=p.id and p.status=:statusPengeluaran and p.tanggal = :tanggal
            join item_keuangan item on pd.item_id=item.id and item.id > :itemTrx and parent_id = :parentId
            join profil on p.profil_id=profil.id
            where pd.posisi=:posisiPengeluaran
         ) t");

        foreach ($parents as $parent) {

            $command->bindValues(array(
                ':tanggal' => $this->tanggal,
                ':itemTrx' => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId' => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_KREDIT,
                ':posisiPenerimaan' => PenerimaanDetail::POSISI_DEBET,
            ));
            $commandTotal->bindValues(array(
                ':tanggal' => $this->tanggal,
                ':itemTrx' => ItemKeuangan::ITEM_TRX_SAJA,
                ':parentId' => $parent->id,
                ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
                ':statusPenerimaan' => Penerimaan::STATUS_BAYAR,
                ':posisiPengeluaran' => PengeluaranDetail::POSISI_KREDIT,
                ':posisiPenerimaan' => PenerimaanDetail::POSISI_DEBET,
            ));
            $jumlah = $commandTotal->queryRow();
            $itemArr[] = array(
                'id' => $parent->id,
                'nama' => $parent->nama,
                'total' => $jumlah['total'],
                'items' => $command->queryAll()
            );
        }
        return $itemArr;
    }

    public function returBeliTunai()
    {
        $sql = "
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
         order by retur_pembelian.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalReturBeliTunai()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $returBeliTunai = $command->queryRow();
        return $returBeliTunai['total'];
    }

    public function returBeliPiutang()
    {
        $sql = "
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
         order by rb.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalReturBeliPiutang()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $piutangReturBeli = $command->queryRow();
        return $piutangReturBeli['total'];
    }

    public function returBeliBayar()
    {
        $sql = "
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
         order by retur_pembelian.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalReturBeliBayar()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_BELI,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $bayarReturBeli = $command->queryRow();
        return $bayarReturBeli['total'];
    }

    public function returJualTunai()
    {
        $sql = "
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
         order by retur_penjualan.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalReturJualTunai()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $returJualTunai = $command->queryRow();
        return $returJualTunai['total'];
    }

    public function returJualHutang()
    {
        $sql = "
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
         order by rb.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah) jumlah
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        return $command->queryAll();
    }

    public function totalReturJualHutang()
    {
        $command = Yii::app()->db->createCommand("
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
         ) t3");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':tanggalAkhir' => $this->tanggalAkhir,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));
        $returJualHutang = $command->queryRow();
        return $returJualHutang['total'];
    }

    public function returJualBayar()
    {
        $sql = "
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
         order by retur_penjualan.nomor";

        if ($this->groupByProfil) {
            $sql = "
                    select distinct nama, sum(jumlah_bayar) jumlah_bayar
                    from ({$sql}) t
                    group by nama
                    order by nama
            ";
        }

        $command = Yii::app()->db->createCommand($sql);

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        return $command->queryAll();
    }

    public function totalReturJualBayar()
    {
        $command = Yii::app()->db->createCommand("
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
         ");

        $command->bindValues(array(
            ':tanggal' => $this->tanggal,
            ':tanggalAwal' => $this->tanggalAwal,
            ':asalHutangPiutang' => HutangPiutang::DARI_RETUR_JUAL,
            ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
            ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
        ));

        $bayarReturJual = $command->queryRow();
        return $bayarReturJual['total'];
    }

}
