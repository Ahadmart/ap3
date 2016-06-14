<?php

/**
 * This is the model class for table "kode_akun".
 *
 * The followings are the available columns in table 'kode_akun':
 * @property string $id
 * @property string $kode
 * @property string $nama
 * @property string $parent_id
 * @property integer $level
 * @property integer $trx
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property KasBank[] $kasBanks
 * @property KodeAkun $parent
 * @property KodeAkun[] $kodeAkuns
 * @property User $updatedBy
 * @property PenerimaanDetail[] $penerimaanDetails
 * @property PengeluaranDetail[] $pengeluaranDetails
 * @property PiutangDetail[] $piutangDetails
 */
class KodeAkun extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'kode_akun';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('kode, nama', 'required'),
            array('level, trx', 'numerical', 'integerOnly' => true),
            array('kode, nama', 'length', 'max' => 45),
            array('parent_id, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, kode, nama, parent_id, level, trx, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'kasBanks' => array(self::HAS_MANY, 'KasBank', 'kode_akun_id'),
            'parent' => array(self::BELONGS_TO, 'KodeAkun', 'parent_id'),
            'kodeAkuns' => array(self::HAS_MANY, 'KodeAkun', 'parent_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'penerimaanDetails' => array(self::HAS_MANY, 'PenerimaanDetail', 'akun_id'),
            'pengeluaranDetails' => array(self::HAS_MANY, 'PengeluaranDetail', 'akun_id'),
            'piutangDetails' => array(self::HAS_MANY, 'PiutangDetail', 'akun_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'kode' => 'Kode',
            'nama' => 'Nama',
            'parent_id' => 'Parent',
            'level' => 'Level',
            'trx' => 'Trx',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('kode', $this->kode, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('parent_id', $this->parent_id, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('trx', $this->trx);
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
     * @return KodeAkun the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->created_at = date('Y-m-d H:i:s');
        }
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

}
