<?php

/**
 * This is the model class for table "pembelian_ppn".
 *
 * The followings are the available columns in table 'pembelian_ppn':
 * @property string $id
 * @property string $pembelian_id
 * @property string $npwp
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
    // const STATUS_DRAFT   = 0;
    const STATUS_PENDING = 10;
    const STATUS_VALID   = 20;
    public $pembelianNomor;
    public $namaUpdatedBy;
    public $pembelianProfil;

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
            ['no_faktur_pajak, total_ppn_faktur', 'required', 'on' => 'validasi'],
            ['status', 'numerical', 'integerOnly' => true],
            ['pembelian_id, updated_by', 'length', 'max' => 10],
            ['npwp', 'length', 'max' => 16],
            ['no_faktur_pajak', 'length', 'max' => 45],
            ['total_ppn_hitung, total_ppn_faktur', 'length', 'max' => 18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, pembelian_id, npwp, no_faktur_pajak, total_ppn_hitung, total_ppn_faktur, status, updated_at, updated_by, created_at, pembelianNomor, namaUpdatedBy, pembelianProfil', 'safe', 'on' => 'search'],
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
            'npwp'             => 'Npwp',
            'no_faktur_pajak'  => 'No Faktur Pajak',
            'total_ppn_hitung' => 'Total Ppn Hitung',
            'total_ppn_faktur' => 'Total Ppn Faktur',
            'status'           => 'Status',
            'updated_at'       => 'Updated At',
            'updated_by'       => 'Updated By',
            'created_at'       => 'Created At',
            'pembelianNomor'   => 'Pembelian',
            'namaStatus'       => 'Status',
            'namaUpdatedBy'    => 'User',
            'pembelianProfil'  => 'Profil',
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
        $criteria->compare('npwp', $this->npwp, true);
        $criteria->compare('no_faktur_pajak', $this->no_faktur_pajak, true);
        $criteria->compare('total_ppn_hitung', $this->total_ppn_hitung, true);
        $criteria->compare('total_ppn_faktur', $this->total_ppn_faktur, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('t.updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);
        $criteria->compare('updatedBy.nama_lengkap', $this->namaUpdatedBy, true);
        $criteria->compare('profil.nama', $this->pembelianProfil, true);

        $criteria->with   = ['pembelian', 'updatedBy', 'pembelian.profil'];
        $criteria->compare('pembelian.nomor', $this->pembelianNomor, true);

        $sort = [
            'defaultOrder' => 'pembelian.nomor desc',
            'attributes'   => [
                '*',
                'pembelianNomor' => [
                    'asc'  => 'pembelian.nomor',
                    'desc' => 'pembelian.nomor desc',
                ],
                'namaUpdatedBy' => [
                    'asc'  => 'updatedBy.nama_lengkap',
                    'desc' => 'updatedBy.nama_lengkap desc',
                ],
                'pembelianProfil' => [
                    'asc'  => 'profil.nama',
                    'desc' => 'profil.nama desc',
                ]
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
        $this->npwp            = str_replace(['.', '-'], '', $this->npwp);
        $this->no_faktur_pajak = str_replace(['.', '-'], '', $this->no_faktur_pajak);
        return parent::beforeValidate();
    }

    public function listStatus()
    {
        return [
            // self::STATUS_DRAFT   => 'Draft',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_VALID   => 'Valid',
        ];
    }

    public function getNamaStatus()
    {
        $list = $this->listStatus();
        return $list[$this->status];
    }

    // public function getPembelianProfil()
    // {
    //     return isset($this->pembelian_id) ? $this->pembelian->profil->nama : '';
    // }
}
