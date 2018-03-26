<?php

/**
 * This is the model class for table "barang_harga_jual_multi".
 *
 * The followings are the available columns in table 'barang_harga_jual_multi':
 * @property string $id
 * @property string $barang_id
 * @property string $satuan_id
 * @property string $qty
 * @property string $harga
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property BarangSatuan $satuan
 * @property User $updatedBy
 */
class HargaJualMulti extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang_harga_jual_multi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['barang_id, satuan_id, harga', 'required'],
            ['barang_id, satuan_id, qty, updated_by', 'length', 'max'=>10],
            ['harga', 'length', 'max'=>18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, barang_id, satuan_id, qty, harga, updated_at, updated_by, created_at', 'safe', 'on'=>'search'],
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
            'satuan'    => [self::BELONGS_TO, 'BarangSatuan', 'satuan_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'barang_id'  => 'Barang',
            'satuan_id'  => 'Satuan',
            'qty'        => 'Qty',
            'harga'      => 'Harga',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('satuan_id', $this->satuan_id, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('harga', $this->harga, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, [
            'criteria'=> $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param  string         $className active record class name.
     * @return HargaJualMulti the static model class
     */
    public static function model($className=__CLASS__)
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
