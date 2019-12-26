<?php

/**
 * This is the model class for table "kas_bank".
 *
 * The followings are the available columns in table 'kas_bank':
 * @property string $id
 * @property string $nama
 * @property string $kode_akun_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $updated_by
 *
 * The followings are the available model relations:
 * @property KodeAkun $kodeAkun
 * @property User $updatedBy
 */
class KasBank extends CActiveRecord
{

    const KAS_ID = 1; // Sementara di sini, nanti pindahkan ke config!

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'kas_bank';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nama', 'required'],
            ['nama', 'length', 'max' => 45],
            ['kode_akun_id, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nama, kode_akun_id, created_at, updated_at, updated_by', 'safe', 'on' => 'search'],
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
            'kodeAkun'  => [self::BELONGS_TO, 'KodeAkun', 'kode_akun_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'nama'         => 'Nama',
            'kode_akun_id' => 'Kode Akun',
            'created_at'   => 'Created At',
            'updated_at'   => 'Updated At',
            'updated_by'   => 'Updated By',
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
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('kode_akun_id', $this->kode_akun_id, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return KasBank the static model class
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
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        if (empty($this->kode_akun_id)) {
            $this->kode_akun_id = null;
        }
        return parent::beforeValidate();
    }

    public function scopes()
    {
        return [
            'kecualiKas' => [
                'condition' => 'id != ' . self::KAS_ID
            ]
        ];
    }

}
