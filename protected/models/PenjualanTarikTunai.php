<?php

/**
 * This is the model class for table "penjualan_tarik_tunai".
 *
 * The followings are the available columns in table 'penjualan_tarik_tunai':
 * @property string $id
 * @property string $penjualan_id
 * @property string $kas_bank_id
 * @property string $jumlah
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Penjualan $penjualan
 * @property KasBank $kasBank
 * @property User $updatedBy
 */
class PenjualanTarikTunai extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penjualan_tarik_tunai';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('penjualan_id, kas_bank_id', 'required'),
            array('penjualan_id, kas_bank_id, updated_by', 'length', 'max' => 10),
            array('jumlah', 'length', 'max' => 18),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, penjualan_id, kas_bank_id, jumlah, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
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
            'penjualan' => array(self::BELONGS_TO, 'Penjualan', 'penjualan_id'),
            'kasBank'   => array(self::BELONGS_TO, 'KasBank', 'kas_bank_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id'           => 'ID',
            'penjualan_id' => 'Penjualan',
            'kas_bank_id'  => 'Kas Bank',
            'jumlah'       => 'Jumlah',
            'updated_at'   => 'Updated At',
            'updated_by'   => 'Updated By',
            'created_at'   => 'Created At',
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
        $criteria->compare('penjualan_id', $this->penjualan_id, true);
        $criteria->compare('kas_bank_id', $this->kas_bank_id, true);
        $criteria->compare('jumlah', $this->jumlah, true);
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
     * @return PenjualanTarikTunai the static model class
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
