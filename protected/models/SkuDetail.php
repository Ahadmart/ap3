<?php

use Mpdf\Tag\P;

/**
 * This is the model class for table "sku_detail".
 *
 * The followings are the available columns in table 'sku_detail':
 * @property string $id
 * @property string $sku_id
 * @property string $barang_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Sku $sku
 * @property User $updatedBy
 */
class SkuDetail extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sku_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['sku_id, barang_id', 'required'],
            ['sku_id, barang_id, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, sku_id, barang_id, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'barang'    => [self::BELONGS_TO, 'Barang', 'barang_id'],
            'sku'       => [self::BELONGS_TO, 'Sku', 'sku_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
            'skuLevel'  => [self::BELONGS_TO, 'SkuLevel', ['satuan_id' => 'satuan_id'], 'through' => 'barang'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'sku_id'     => 'Sku',
            'barang_id'  => 'Barang',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'namaBarang' => 'Nama',
            'namaSatuan' => 'Satuan',
            'namaRak'    => 'Rak',
            'skuLevel'   => 'Level'
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
    public function search($merge = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('t.sku_id', $this->sku_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('t.created_at', $this->created_at, true);

        $criteria->with = ['barang', 'barang.satuan'];

        $criteria->join = 'JOIN barang b on b.id = t.barang_id';
        $criteria->join .= ' LEFT JOIN sku_level ON sku_level.satuan_id = b.satuan_id AND sku_level.sku_id = t.sku_id';

        $sort = [
            // 'defaultOrder' => 'COALESCE(level.level, -1) DESC',
            'defaultOrder' => 'sku_level.level DESC, barang.nama',
            'attributes'   => [
                '*',
                'barcode'    => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc',
                ],
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc',
                ],
                'namaSatuan' => [
                    'asc'  => 'satuan.nama',
                    'desc' => 'satuan.nama desc',
                ],
                'namaRak' => [
                    'asc'  => 'rak.nama',
                    'desc' => 'rak.nama desc',
                ],
                'level' => [
                    'asc'  => 'sku_level.level',
                    'desc' => 'sku_level.level desc'
                ]
            ],
        ];
        if ($merge !== null) {
            $criteria->mergeWith($merge, false);
        }
        // echo 'Conditions: ' . $criteria->condition . '<br>';
        // echo 'Params: ' . print_r($criteria->params, true) . '<br>';
        // echo 'Order: ' . $criteria->order . '<br>';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SkuDetail the static model class
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
}
