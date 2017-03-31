<?php

/**
 * This is the model class for table "penerimaan".
 *
 * The followings are the available columns in table 'penerimaan':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $keterangan
 * @property string $profil_id
 * @property string $kas_bank_id
 * @property string $kategori_id
 * @property string $jenis_transaksi_id
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property string $uang_dibayar
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property JenisTransaksi $jenisTransaksi
 * @property KasBank $kasBank
 * @property PenerimaanKategori $kategori
 * @property Profil $profil
 * @property User $updatedBy
 * @property PenerimaanDetail[] $penerimaanDetails
 */
class Penerimaan extends CActiveRecord
{

    const STATUS_DRAFT = 0;
    const STATUS_BAYAR = 1;

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;
    public $namaUpdatedBy;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penerimaan';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('profil_id, kas_bank_id, kategori_id, jenis_transaksi_id', 'required', 'message' => '{attribute} tidak boleh kosong'),
            array('status', 'numerical', 'integerOnly' => true),
            array('nomor, referensi', 'length', 'max' => 45),
            array('uang_dibayar', 'length', 'max' => 18),
            array('keterangan', 'length', 'max' => 500),
            array('profil_id, kas_bank_id, kategori_id, jenis_transaksi_id, updated_by', 'length', 'max' => 10),
            array('tanggal_referensi, created_at, updated_at, updated_by, tanggal', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, nomor, tanggal, keterangan, profil_id, kas_bank_id, kategori_id, jenis_transaksi_id, referensi, tanggal_referensi, status, updated_at, updated_by, created_at, namaProfil, namaUpdatedBy', 'safe', 'on' => 'search'),
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
            'jenisTransaksi' => array(self::BELONGS_TO, 'JenisTransaksi', 'jenis_transaksi_id'),
            'kasBank' => array(self::BELONGS_TO, 'KasBank', 'kas_bank_id'),
            'kategori' => array(self::BELONGS_TO, 'KategoriPenerimaan', 'kategori_id'),
            'profil' => array(self::BELONGS_TO, 'Profil', 'profil_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'penerimaanDetails' => array(self::HAS_MANY, 'PenerimaanDetail', 'penerimaan_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nomor' => 'Nomor',
            'tanggal' => 'Tanggal',
            'keterangan' => 'Keterangan',
            'profil_id' => 'Profil',
            'kas_bank_id' => 'K/B',
            'kategori_id' => 'Kategori',
            'jenis_transaksi_id' => 'Jenis Tr',
            'referensi' => 'Ref',
            'tanggal_referensi' => 'Tgl Ref',
            'uang_dibayar' => 'Uang Dibayar',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaProfil' => 'Profil',
            'namaUpdatedBy' => 'User'
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
        $criteria->compare('t.nomor', $this->nomor, true);
        $criteria->compare("DATE_FORMAT(t.tanggal, '%d-%m-%Y')", $this->tanggal, true);
        $criteria->compare('t.keterangan', $this->keterangan, true);
        $criteria->compare('profil_id', $this->profil_id, true);
        $criteria->compare('kas_bank_id', $this->kas_bank_id);
        $criteria->compare('kategori_id', $this->kategori_id);
        $criteria->compare('jenis_transaksi_id', $this->jenis_transaksi_id);
        $criteria->compare('referensi', $this->referensi, true);
        $criteria->compare("DATE_FORMAT(tanggal_referensi, '%d-%m-%Y')", $this->tanggal_referensi, true);
        $criteria->compare('uang_dibayar', $this->uang_dibayar, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['profil', 'updatedBy'];
        $criteria->compare('profil.nama', $this->namaProfil, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);

        $sort = [
            'defaultOrder' => 't.status, t.tanggal desc, t.nomor desc',
            'attributes' => [
                '*',
                'namaJenisTr' => [
                    'asc' => 'jenisTransaksi.nama',
                    'desc' => 'jenisTransaksi.nama desc'
                ],
                'namaKasBank' => [
                    'asc' => 'kasBank.nama',
                    'desc' => 'kasBank.nama desc'
                ],
                'namaKategori' => [
                    'asc' => 'kategori.nama',
                    'desc' => 'kategori.nama desc'
                ],
                'namaProfil' => [
                    'asc' => 'profil.nama',
                    'desc' => 'profil.nama desc'
                ],
                'namaUpdatedBy' => [
                    'asc' => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc'
                ],
            ]
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => $sort
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Penerimaan the static model class
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
        if ($this->scenario === 'proses') {
            if ($this->ambilTotal() < 0) {
                throw new Exception("Jumlah tidak boleh < 0", 500);
            }
            $this->status = Penerimaan::STATUS_BAYAR;
            $this->nomor = $this->generateNomor6Seq();
            // $this->tanggal = date('Y-m-d H:i:s');
        }
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        $this->tanggal = !empty($this->tanggal) ? date_format(DateTime::createFromFormat('d-m-Y', $this->tanggal), 'Y-m-d') : NULL;
        $this->tanggal_referensi = !empty($this->tanggal_referensi) ? date_format(date_create_from_format('d-m-Y', $this->tanggal_referensi), 'Y-m-d') : NULL;
        return parent::beforeValidate();
    }

    public function afterFind()
    {
        $this->tanggal = !is_null($this->tanggal) ? date_format(date_create_from_format('Y-m-d', $this->tanggal), 'd-m-Y') : '0';
        $this->tanggal_referensi = !is_null($this->tanggal_referensi) ? date_format(date_create_from_format('Y-m-d', $this->tanggal_referensi), 'd-m-Y') : '';
        return parent::afterFind();
    }

    /**
     * Total Penerimaan
     * @return int Nilai Total
     */
    public function ambilTotal()
    {
        $pengeluaran = Yii::app()->db->createCommand()
                ->select('SUM(
                        CASE
                         WHEN posisi=0 THEN +jumlah
                         WHEN posisi=1 THEN -jumlah
                        END) total')
                ->from(PenerimaanDetail::model()->tableName())
                ->where('penerimaan_id=:penerimaanId', array(':penerimaanId' => $this->id))
                ->queryRow();
        return $pengeluaran['total'];
    }

    /**
     * Hutang piutang yang sudah dibayarkan, sekalipun status penerimaan masih draft
     * @param int $hutangPiutangId
     * @return int Jumlah yang sudah ada di penerimaan_detail
     */
    public function totalSudahBayar($hutangPiutangId)
    {
        $penerimaan = Yii::app()->db->createCommand('
							   SELECT hutang_piutang_id,sum(jumlah) jumlah
								FROM penerimaan_detail
								WHERE hutang_piutang_id = :hutangPiutangId
								GROUP BY hutang_piutang_id')
                ->bindValue(":hutangPiutangId", $hutangPiutangId)
                ->queryRow();
        return $penerimaan ? $penerimaan['jumlah'] : 0;
    }

    /**
     * Nilai total penerimaan
     * @return text Total Penerimaan dalam format ribuan
     */
    public function getTotal()
    {
        return number_format($this->ambilTotal(), 0, ',', '.');
    }

    public function getNamaStatus()
    {
        $namaStatus = array('Draft', 'Paid');
        return $namaStatus[$this->status];
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data = $this->find(array(
            'select' => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}'")
        );

        $value = is_null($data) ? 0 : $data->max;
        return $value + 1;
    }

    /**
     * Membuat nomor surat, 6 digit sequence number
     * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
     */
    public function generateNomor6Seq()
    {
        $config = Config::model()->find("nama='toko.kode'");
        $kodeCabang = $config->nilai;
        $kodeDokumen = KodeDokumen::PENERIMAAN;
        $kodeTahunBulan = date('ym');
        $sequence = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public function listFilterStatus()
    {
        return array(
            Pengeluaran::STATUS_DRAFT => 'Draft',
            Pengeluaran::STATUS_BAYAR => 'Paid'
        );
    }

    public function listFilterKasBank()
    {
        return CHtml::listData(KasBank::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function listFilterKategori()
    {
        return CHtml::listData(KategoriPenerimaan::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function listFilterJenisTransaksi()
    {
        return CHtml::listData(JenisTransaksi::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function prosesP()
    {
        $this->scenario = 'proses';
        if ($this->save()) {
            // Ambil details yang hutang piutang untuk diproses lebih lanjut
            $details = PenerimaanDetail::model()->findAll('penerimaan_id=:penerimaanId and hutang_piutang_id is not null', array(':penerimaanId' => $this->id));
            foreach ($details as $detail) {
                $hutangPiutang = HutangPiutang::model()->findByPk($detail->hutang_piutang_id);
                // Bayar dan simpan
                if (!($hutangPiutang->bayar() && $hutangPiutang->save())) {
                    throw new Exception("Gagal proses bayar hutang piutang");
                }
            }
            return true;
        } else {
            throw new Exception("Gagal Proses");
        }
    }

    public function proses()
    {
        $this->scenario = 'proses';
        $transaction = $this->dbConnection->beginTransaction();
        try {
            if ($this->prosesP()) {
                $transaction->commit();
                return true;
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

}
