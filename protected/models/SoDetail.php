<?php

/**
 * This is the model class for table "so_detail".
 *
 * The followings are the available columns in table 'so_detail':
 * @property string $id
 * @property string $so_id
 * @property string $barang_id
 * @property string $qty
 * @property string $harga_jual
 * @property string $diskon
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property So $so
 * @property User $updatedBy
 */
class SoDetail extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'so_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['so_id, barang_id, harga_jual', 'required'],
            ['so_id, barang_id, qty, updated_by', 'length', 'max' => 10],
            ['harga_jual, diskon', 'length', 'max' => 18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, so_id, barang_id, qty, harga_jual, diskon, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'so'        => [self::BELONGS_TO, 'So', 'so_id'],
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
            'so_id'      => 'Sales Order',
            'barang_id'  => 'Barang',
            'qty'        => 'Qty',
            'harga_jual' => 'Harga Jual',
            'diskon'     => 'Diskon',
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
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('so_id', $this->so_id);
        $criteria->compare('barang_id', $this->barang_id);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('harga_jual', $this->harga_jual, true);
        $criteria->compare('diskon', $this->diskon, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = [
            'defaultOrder' => 't.id desc'
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
     * @return SoDetail the static model class
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

    public function getTotal()
    {
        return number_format($this->harga_jual * $this->qty, 0, ',', '.');
    }

}
