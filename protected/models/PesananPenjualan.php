<?php

/**
 * This is the model class for table "pesanan_penjualan".
 *
 * The followings are the available columns in table 'pesanan_penjualan':
 * @property string $id
 * @property string $nomor
 * @property string $tanggal
 * @property string $profil_id
 * @property string $penjualan_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Penjualan $penjualan
 * @property Profil $profil
 * @property User $updatedBy
 * @property PesananPenjualanDetail[] $pesananPenjualanDetails
 */
class PesananPenjualan extends Penjualan
{

    const STATUS_DRAFT = 0;
    const STATUS_PESAN = 10;
    const STATUS_BATAL = 20;
    const STATUS_JUAL  = 30;

    public $max; // Untuk mencari untuk nomor surat;
    public $namaProfil;
    public $namaUser;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pesanan_penjualan';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['profil_id', 'required'],
            ['status', 'numerical', 'integerOnly' => true],
            ['nomor', 'length', 'max' => 45],
            ['profil_id, penjualan_id, updated_by', 'length', 'max' => 10],
            ['tanggal, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nomor, tanggal, profil_id, penjualan_id, status, updated_at, updated_by, created_at, namaProfil', 'safe', 'on' => 'search'],
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
            'penjualan'               => [self::BELONGS_TO, 'Penjualan', 'penjualan_id'],
            'profil'                  => [self::BELONGS_TO, 'Profil', 'profil_id'],
            'updatedBy'               => [self::BELONGS_TO, 'User', 'updated_by'],
            'pesananPenjualanDetails' => [self::HAS_MANY, 'PesananPenjualanDetail', 'pesanan_penjualan_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'nomor'        => 'Nomor',
            'tanggal'      => 'Tanggal',
            'profil_id'    => 'Profil',
            'penjualan_id' => 'Penjualan',
            'status'       => 'Status',
            'updated_at'   => 'Updated At',
            'updated_by'   => 'Updated By',
            'created_at'   => 'Created At',
            'namaProfil'   => 'Profil',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('nomor', $this->nomor, true);
        $criteria->compare('tanggal', $this->tanggal, true);
        $criteria->compare('profil_id', $this->profil_id);
        $criteria->compare('penjualan_id', $this->penjualan_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['profil', 'updatedBy'];
        $criteria->compare('profil.nama', $this->namaProfil, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUser, true);

        $sort = [
            'defaultOrder' => 'case t.status when ' . self::STATUS_DRAFT . ' then 0 '
            . 'when ' . self::STATUS_PESAN . ' then 1 '
            . 'when ' . self::STATUS_JUAL . ' then 2 '
            . 'else 3 end, t.tanggal desc',
            'attributes'   => [
                '*',
                'namaProfil' => [
                    'asc'  => 'profil.nama',
                    'desc' => 'profil.nama desc'
                ],
                'namaUser'   => [
                    'asc'  => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc'
                ],
            ]
        ];
        return new CActiveDataProvider($this,
                [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PesananPenjualan the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
            $this->tanggal    = date('Y-m-d H:i:s');
        }
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        if ($this->scenario === 'simpanPertama') {
            $this->status  = self::STATUS_PESAN;
            // Dapat nomor dan tanggal baru
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor   = $this->generateNomor6Seq();
        }
        return parent::beforeSave();
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomor()
    {
        $tahun = date('y');
        $data  = $this->find([
            'select'    => 'max(substring(nomor,9)*1) as max',
            'condition' => "substring(nomor,5,2)='{$tahun}'"]
        );

        $value = is_null($data) ? 0 : $data->max;
        return $value + 1;
    }

    /**
     * Membuat nomor surat
     * @return string Nomor sesuai format "[KodeCabang][kodeDokumen][Tahun][Bulan][SequenceNumber]"
     */
    public function generateNomor6Seq()
    {
        $config         = Config::model()->find("nama='toko.kode'");
        $kodeCabang     = $config->nilai;
        $kodeDokumen    = KodeDokumen::PESANAN_PENJUALAN;
        $kodeTahunBulan = date('ym');
        $sequence       = substr('00000' . $this->cariNomor(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public function beforeValidate()
    {
        $this->profil_id = empty($this->profil_id) ? Profil::PROFIL_UMUM : $this->profil_id;
        return parent::beforeValidate();
    }

    /**
     * Tambah barang ke detail
     * @param string $barcode
     * @param int $qty
     * @return array
     * @throws Exception
     */
    public function tambahBarang($barcode, $qty, $cekLimit = false)
    {
        $transaction = $this->dbConnection->beginTransaction();
        try {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);

            /* Jika barang tidak ada */
            if (is_null($barang)) {
                throw new Exception('Barang tidak ditemukan', 500);
            }
            $this->tambahBarangProc($barang, $qty);

            $transaction->commit();
            return [
                'sukses' => true
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ]];
        }
    }

    /**
     * Mencari jumlah barang di tabel pesanan_penjualan_detail
     * @param int $barangId ID Barang
     * @return int qty / jumlah barang, FALSE jika tidak ada
     */
    public function barangAda($barangId)
    {
        $detail = Yii::app()->db->createCommand("
        select sum(qty) qty from pesanan_penjualan_detail
        where pesanan_penjualan_id=:pesananId and barang_id=:barangId
            ")->bindValues([':pesananId' => $this->id, ':barangId' => $barangId])
                ->queryRow();

        return $detail['qty'];
    }

    /**
     * Hapus barang di pesanan_penjualan_detail
     * @param ActiveRecord $barang
     */
    public function cleanBarang($barang)
    {
        PesananPenjualan::model()->deleteAll('barang_id=:barangId AND pesanan_penjualan_id=:pesananId',
                [
            ':barangId'  => $barang->id,
            ':pesananId' => $this->id
        ]);
    }

    /**
     * Insert Pesanan Detail
     * @param int $barangId
     * @param int $qty
     * @param decimal $hargaJual
     * @param decimal $diskon
     * @param int $tipeDiskonId
     * @throws Exception
     */
    public function insertBarang($barangId, $qty, $hargaJual, $diskon = 0, $tipeDiskonId = null, $multiHJ = [])
    {
        $detail                       = new PesananPenjualanDetail;
        $detail->pesanan_penjualan_id = $this->id;
        $detail->barang_id            = $barangId;
        $detail->qty                  = $qty;
        $detail->harga_jual           = $hargaJual;
        if ($diskon > 0) {
            $detail->diskon = $diskon;
        }
        if (!$detail->save()) {
            throw new Exception("Gagal simpan pesanan detail: pesananID:{$this->id}, barangID:{$barangId}, qty:{$qty}",
            500);
        }
    }

    /**
     * Total Pesanan
     * @return int total dalam bentuk raw (belum terformat)
     */
    public function ambilTotal()
    {
        $detail = Yii::app()->db->createCommand()
                ->select('sum(harga_jual * qty) total')
                ->from(PesananPenjualanDetail::model()->tableName())
                ->where('pesanan_penjualan_id=:pesananId', [':pesananId' => $this->id])
                ->queryRow();
        return $detail['total'];
    }

    /**
     * Total Pesanan
     * @return string Total dalam format 0.000
     */
    public function getTotal()
    {
        return number_format($this->ambilTotal(), 0, ',', '.');
    }

    public function simpan()
    {
        $transaction    = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanPertama';
        try {
            $this->simpanPesanan();
            $transaction->commit();
            return [
                'sukses' => true
            ];
        } catch (Exception $ex) {
            $transaction->rollback();
            return [
                'sukses' => false,
                'error'  => [
                    'msg'  => $ex->getMessage(),
                    'code' => $ex->getCode(),
            ]];
        }
    }

    public function simpanPesanan()
    {
        if (!$this->save()) {
            throw new Exception('Gagal simpan Pesanan', 500);
        }
    }

    public function listStatus()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PESAN => 'Pesan',
            self::STATUS_JUAL  => 'Jual',
            self::STATUS_BATAL => 'Batal',
        ];
    }

}
