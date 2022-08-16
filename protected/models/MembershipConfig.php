<?php

/**
 * This is the model class for table "membership_config".
 *
 * The followings are the available columns in table 'membership_config':
 * @property string $id
 * @property string $nama
 * @property string $nilai
 * @property string $deskripsi
 * @property integer $show
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class MembershipConfig extends CActiveRecord
{
    const VISIBLE = 1;
    const HIDDEN  = 0;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'membership_config';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nama, nilai, updated_at, updated_by', 'required'],
            ['show', 'numerical', 'integerOnly' => true],
            ['nama', 'length', 'max' => 45],
            ['nilai', 'length', 'max' => 255],
            ['deskripsi', 'length', 'max' => 1000],
            ['updated_by', 'length', 'max' => 10],
            ['created_at', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nama, nilai, deskripsi, show, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'nama'       => 'Nama',
            'nilai'      => 'Nilai',
            'deskripsi'  => 'Deskripsi',
            'show'       => 'Show',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('nilai', $this->nilai, true);
        $criteria->compare('deskripsi', $this->deskripsi, true);
        $criteria->compare('t.show', $this->show);
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
     * @return MembershipConfig the static model class
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

    public function scopes()
    {
        return [
            'visibleOnly' => [
                'condition' => 't.show = ' . self::VISIBLE
            ]
        ];
    }
}
