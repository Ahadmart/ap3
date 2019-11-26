<?php

/**
 * This is the model class for table "penerimaan_kas_bank".
 *
 * The followings are the available columns in table 'penerimaan_kas_bank':
 * @property string $id
 * @property string $penerimaan_id
 * @property string $kas_bank_id
 * @property string $keterangan
 * @property string $jumlah
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Penerimaan $penerimaan
 * @property KasBank $kasBank
 * @property User $updatedBy
 */
class PenerimaanKasBank extends CActiveRecord
{

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penerimaan_kas_bank';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['penerimaan_id, kas_bank_id', 'required'],
            ['penerimaan_id, kas_bank_id, updated_by', 'length', 'max' => 10],
            ['keterangan', 'length', 'max' => 255],
            ['jumlah', 'length', 'max' => 18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, penerimaan_id, kas_bank_id, keterangan, jumlah, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'penerimaan' => [self::BELONGS_TO, 'Penerimaan', 'penerimaan_id'],
            'kasBank'    => [self::BELONGS_TO, 'KasBank', 'kas_bank_id'],
            'updatedBy'  => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'penerimaan_id' => 'Penerimaan',
            'kas_bank_id'   => 'Kas Bank',
            'keterangan'    => 'Keterangan',
            'jumlah'        => 'Jumlah',
            'updated_at'    => 'Updated At',
            'updated_by'    => 'Updated By',
            'created_at'    => 'Created At',
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
        $criteria->compare('penerimaan_id', $this->penerimaan_id, true);
        $criteria->compare('kas_bank_id', $this->kas_bank_id, true);
        $criteria->compare('keterangan', $this->keterangan, true);
        $criteria->compare('jumlah', $this->jumlah, true);
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
     * @return PenerimaanKasBank the static model class
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
