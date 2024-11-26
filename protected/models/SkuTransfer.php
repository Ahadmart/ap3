<?php

/**
 * This is the model class for table "sku_transfer".
 *
 * The followings are the available columns in table 'sku_transfer':
 * @property string $id
 * @property string $sku_id
 * @property string $tanggal
 * @property string $nomor
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property string $keterangan
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Sku $sku
 * @property User $updatedBy
 * @property SkuTransferDetail[] $skuTransferDetails
 */
class SkuTransfer extends CActiveRecord
{
    const STATUS_DRAFT    = 0;
    const STATUS_TRANSFER = 1;

    public $max; // Untuk mencari untuk nomor surat;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sku_transfer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['sku_id', 'required'],
            ['status', 'numerical', 'integerOnly' => true],
            ['sku_id, updated_by', 'length', 'max' => 10],
            ['nomor, referensi', 'length', 'max' => 45],
            ['keterangan', 'length', 'max' => 500],
            ['tanggal, tanggal_referensi, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, sku_id, tanggal, nomor, referensi, tanggal_referensi, keterangan, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'sku'                => [self::BELONGS_TO, 'Sku', 'sku_id'],
            'updatedBy'          => [self::BELONGS_TO, 'User', 'updated_by'],
            'skuTransferDetails' => [self::HAS_MANY, 'SkuTransferDetail', 'sku_transfer_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                => 'ID',
            'sku_id'            => 'Sku',
            'tanggal'           => 'Tanggal',
            'nomor'             => 'Nomor',
            'referensi'         => 'Referensi',
            'tanggal_referensi' => 'Tanggal Referensi',
            'keterangan'        => 'Keterangan',
            'status'            => 'Status',
            'updated_at'        => 'Updated At',
            'updated_by'        => 'Updated By',
            'created_at'        => 'Created At',
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
        $criteria->compare('sku_id', $this->sku_id, true);
        $criteria->compare('tanggal', $this->tanggal, true);
        $criteria->compare('nomor', $this->nomor, true);
        $criteria->compare('referensi', $this->referensi, true);
        $criteria->compare('tanggal_referensi', $this->tanggal_referensi, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('status', $this->status);
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
     * @return SkuTransfer the static model class
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
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        if ($this->scenario === 'simpanTransfer') {
            // Status diubah jadi transfer
            $this->status = self::STATUS_TRANSFER;
            // Dapat nomor dan tanggal
            $this->tanggal = date('Y-m-d H:i:s');
            $this->nomor   = $this->generateNomor6Seq();
        }
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        $this->tanggal_referensi = empty($this->tanggal_referensi) ? NULL : date_format(date_create_from_format('d-m-Y', $this->tanggal_referensi), 'Y-m-d');
        return parent::beforeValidate();
    }

    /**
     * Mencari nomor untuk penomoran surat
     * @return int maksimum+1 atau 1 jika belum ada nomor untuk tahun ini
     */
    public function cariNomorTahunan()
    {
        $tahun = date('y');
        $data  = $this->find(
            [
                'select'    => 'max(substring(nomor,9)*1) as max',
                'condition' => "substring(nomor,5,2)='{$tahun}'"
            ]
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
        $config         = Config::model()->find("nama='toko.kode'");
        $kodeCabang     = $config->nilai;
        $kodeDokumen    = KodeDokumen::TRANSFER_STOK;
        $kodeTahunBulan = date('ym');
        $sequence       = substr('00000' . $this->cariNomorTahunan(), -6);
        return "{$kodeCabang}{$kodeDokumen}{$kodeTahunBulan}{$sequence}";
    }

    public function simpan(){
        $tr = $this->dbConnection->beginTransaction();
        $this->scenario = 'simpanTransfer';
        Yii::log('simpan()');
        try {
            Yii::log('$this->simpanTransfer()');
            $this->simpanTransfer();
            $tr->commit();
        } catch (Exception $e){
            $tr->rollback();
            throw $e;
        }
    }

    private function simpanTransfer(){
        // Yii::log('simpanTransfer() 1');
        if (!$this->save()){
            Yii::log('Gagal simpan transfer');
            throw new Exception('Gagal simpan transfer', 500);
        }
        // Yii::log('simpanTransfer() 2');
        // Detail hanya 1 baris
        $detail = SkuTransferDetail::model()->find('sku_transfer_id = :id', [':id' => $this->id]);     

        // Yii::log('simpan | detail: ' . var_export($detail, true));
        $ib = new InventoryBalance();
        $ib->bukaKemasan($detail);
        
    }
}
