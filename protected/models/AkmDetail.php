<?php

/**
 * This is the model class for table "akm_detail".
 *
 * The followings are the available columns in table 'akm_detail':
 * @property string $id
 * @property string $akm_id
 * @property string $barang_id
 * @property string $qty
 * @property string $harga_jual
 * @property string $diskon
 * @property string $updated_at
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Akm $akm
 */
class AkmDetail extends CActiveRecord
{

    public $barcode;
    public $namaBarang;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'akm_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['akm_id, barang_id, harga_jual', 'required'],
            ['akm_id, barang_id, qty', 'length', 'max' => 10],
            ['harga_jual, diskon', 'length', 'max' => 18],
            ['created_at, updated_at', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, akm_id, barang_id, qty, harga_jual, diskon, updated_at, created_at', 'safe', 'on' => 'search'],
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
            'akm' => [self::BELONGS_TO, 'Akm', 'akm_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'akm_id' => 'Akm',
            'barang_id' => 'Barang',
            'qty' => 'Qty',
            'harga_jual' => 'Harga',
            'diskon' => 'Diskon',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
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
        $criteria->compare('akm_id', $this->akm_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('harga_jual', $this->harga_jual, true);
        $criteria->compare('diskon', $this->diskon, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort' => [
                'defaultOrder' => 'id desc'
            ]
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AkmDetail the static model class
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
        ; // current timestamp
        return parent::beforeSave();
    }

}
