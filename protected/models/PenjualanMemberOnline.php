<?php

/**
 * This is the model class for table "penjualan_member_online".
 *
 * The followings are the available columns in table 'penjualan_member_online':
 * @property string $id
 * @property string $nomor_member
 * @property string $penjualan_id
 * @property string $poin_cashback_dipakai
 * @property string $poin_utama
 * @property string $poin_cashback
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Penjualan $penjualan
 * @property User $updatedBy
 */
class PenjualanMemberOnline extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'penjualan_member_online';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nomor_member, penjualan_id, poin_cashback_dipakai, poin_utama, poin_cashback', 'required'],
            ['nomor_member', 'length', 'max'=>45],
            ['penjualan_id, poin_cashback_dipakai, poin_utama, poin_cashback, updated_by', 'length', 'max'=>10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nomor_member, penjualan_id, poin_cashback_dipakai, poin_utama, poin_cashback, updated_at, updated_by, created_at', 'safe', 'on'=>'search'],
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
            'penjualan' => [self::BELONGS_TO, 'Penjualan', 'penjualan_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                    => 'ID',
            'nomor_member'          => 'Nomor Member',
            'penjualan_id'          => 'Penjualan',
            'poin_cashback_dipakai' => 'Poin Cashback Dipakai',
            'poin_utama'            => 'Poin Utama',
            'poin_cashback'         => 'Poin Cashback',
            'updated_at'            => 'Updated At',
            'updated_by'            => 'Updated By',
            'created_at'            => 'Created At',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('nomor_member', $this->nomor_member, true);
        $criteria->compare('penjualan_id', $this->penjualan_id, true);
        $criteria->compare('poin_cashback_dipakai', $this->poin_cashback_dipakai, true);
        $criteria->compare('poin_utama', $this->poin_utama, true);
        $criteria->compare('poin_cashback', $this->poin_cashback, true);
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
     * @param string $className active record class name.
     * @return PenjualanMemberOnline the static model class
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
        $this->updated_at = date('Y-m-d H:i:s');
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }
}
