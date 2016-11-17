<?php

/**
 * This is the model class for table "rekap_ads".
 *
 * The followings are the available columns in table 'rekap_ads':
 * @property string $barang_id
 * @property integer $qty
 * @property double $ads
 * @property integer $stok
 * @property double $sisa_hari
 * @property string $updated_at
 */
class RekapAds extends CActiveRecord
{

    public $namaBarang;
    public $barcode;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'rekap_ads';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['barang_id, updated_at', 'required'],
            ['qty, stok', 'numerical', 'integerOnly' => true],
            ['ads, sisa_hari', 'numerical'],
            ['barang_id', 'length', 'max' => 10],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['barang_id, qty, ads, stok, sisa_hari, updated_at', 'safe', 'on' => 'search'],
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
            'barang' => [self::BELONGS_TO, 'Barang', 'barang_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang',
            'qty' => 'Penjualan (qty)',
            'ads' => 'Penjualan/Hari',
            'stok' => 'Stok',
            'sisa_hari' => 'Estimasi Sisa Hari',
            'updated_at' => 'Updated At',
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

        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('qty', $this->qty);
        $criteria->compare('ads', $this->ads);
        $criteria->compare('stok', $this->stok);
        $criteria->compare('sisa_hari', $this->sisa_hari);
        $criteria->compare('updated_at', $this->updated_at, true);

        $criteria->with = ['barang'];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 'sisa_hari, qty desc, barang.nama',
                'attributes' => [
                    '*',
                    'namaBarang' => [
                        'asc' => 'barang.nama',
                        'desc' => 'barang.nama desc'
                    ],
                    'barcode' => [
                        'asc' => 'barang.barcode',
                        'desc' => 'barang.barcode desc'
                    ]
                ]
            ]
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return RekapAds the static model class
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

}
