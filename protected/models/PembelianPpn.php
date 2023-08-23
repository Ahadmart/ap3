<?php

/**
 * This is the model class for table "pembelian_ppn".
 *
 * The followings are the available columns in table 'pembelian_ppn':
 * @property string $id
 * @property string $pembelian_id
 * @property string $no_faktur_pajak
 * @property string $total_ppn_hitung
 * @property string $total_ppn_faktur
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Pembelian $pembelian
 * @property User $updatedBy
 */
class PembelianPpn extends CActiveRecord
{
    const STATUS_DRAFT   = 0;
    const STATUS_PENDING = 10;
    const STATUS_VALID   = 20;
    public $pembelianNomor;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pembelian_ppn';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['pembelian_id, total_ppn_hitung', 'required'],
            ['status', 'numerical', 'integerOnly' => true],
            ['pembelian_id, updated_by', 'length', 'max' => 10],
            ['no_faktur_pajak', 'length', 'max' => 45],
            ['total_ppn_hitung, total_ppn_faktur', 'length', 'max' => 18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, pembelian_id, no_faktur_pajak, total_ppn_hitung, total_ppn_faktur, status, updated_at, updated_by, created_at, pembelianNomor', 'safe', 'on' => 'search'],
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
            'pembelian' => [self::BELONGS_TO, 'Pembelian', 'pembelian_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'pembelian_id'     => 'Pembelian',
            'no_faktur_pajak'  => 'No Faktur Pajak',
            'total_ppn_hitung' => 'Total Ppn Hitung',
            'total_ppn_faktur' => 'Total Ppn Faktur',
            'status'           => 'Status',
            'updated_at'       => 'Updated At',
            'updated_by'       => 'Updated By',
            'created_at'       => 'Created At',
            'pembelianNomor'   => 'Pembelian',
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
        $criteria->compare('pembelian_id', $this->pembelian_id, true);
        $criteria->compare('no_faktur_pajak', $this->no_faktur_pajak, true);
        $criteria->compare('total_ppn_hitung', $this->total_ppn_hitung, true);
        $criteria->compare('total_ppn_faktur', $this->total_ppn_faktur, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with   = ['pembelian'];
        $criteria->select = 't.*, p.*';
        $criteria->join   = 'right join pembelian p on p.id = t.pembelian_id';
        $criteria->compare('p.nomor', $this->pembelianNomor, true);

        $sort = [
            'defaultOrder' => 'p.nomor desc',
            'attributes'   => [
                '*',
                'pembelianNomor' => [
                    'asc'  => 'p.nomor',
                    'desc' => 'p.nomor desc',
                ],
            ],
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'     => $sort,
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PembelianPpn the static model class
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

    public function beforeValidate()
    {
        $this->no_faktur_pajak = str_replace(['.', '-'], '', $this->no_faktur_pajak);
        return parent::beforeValidate();
    }
}
