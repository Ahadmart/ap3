<?php

/**
 * This is the model class for table "barang_diskon".
 *
 * The followings are the available columns in table 'barang_diskon':
 * @property string $id
 * @property string $barang_id
 * @property integer $tipe_diskon_id
 * @property string $nominal
 * @property double $persen
 * @property string $dari
 * @property string $sampai
 * @property string $qty
 * @property string $qty_min
 * @property string $qty_max
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property User $updatedBy
 */
class DiskonBarang extends CActiveRecord
{

    const TIPE_PROMO = 0;
    const TIPE_GROSIR = 1;
    const TIPE_BANDED = 2;
    /* ========= */
    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF = 1;
    
    public $barcode;
    public $namaBarang;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang_diskon';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('barang_id, tipe_diskon_id, nominal, dari', 'required'),
            array('tipe_diskon_id, status', 'numerical', 'integerOnly' => true),
            array('persen', 'numerical'),
            array('barang_id, qty, qty_min, qty_max, updated_by', 'length', 'max' => 10),
            array('nominal', 'length', 'max' => 18),
            array('sampai, created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, barang_id, tipe_diskon_id, nominal, persen, dari, sampai, qty, qty_min, qty_max, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
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
            'barang_id' => 'Barang',
            'tipe_diskon_id' => 'Tipe Diskon',
            'nominal' => 'Diskon (Nominal)',
            'persen' => 'Diskon (%)',
            'dari' => 'Dari',
            'sampai' => 'Sampai',
            'qty' => 'Qty',
            'qty_min' => 'Qty Min',
            'qty_max' => 'Qty Max',
            'status' => 'Status',
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
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('tipe_diskon_id', $this->tipe_diskon_id);
        $criteria->compare('nominal', $this->nominal, true);
        $criteria->compare('persen', $this->persen);
        $criteria->compare('dari', $this->dari, true);
        $criteria->compare('sampai', $this->sampai, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('qty_min', $this->qty_min, true);
        $criteria->compare('qty_max', $this->qty_max, true);
        $criteria->compare('status', $this->status);
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
     * @return DiskonBarang the static model class
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
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function listTipe()
    {
        return array(
            self::TIPE_PROMO => 'Promo (pengurangan harga per waktu tertentu)',
            self::TIPE_GROSIR => 'Grosir (beli banyak harga turun)',
            self::TIPE_BANDED => 'Banded (beli qty tertentu harga turun)'
        );
    }

    public function listStatus()
    {
        return array(
            self::STATUS_TIDAK_AKTIF => 'Tidak Aktif',
            self::STATUS_AKTIF => 'Aktif',
        );
    }

}
