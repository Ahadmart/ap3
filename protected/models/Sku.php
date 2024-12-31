<?php

/**
 * This is the model class for table "sku".
 *
 * The followings are the available columns in table 'sku':
 * @property string $id
 * @property string $nomor
 * @property string $nama
 * @property string $struktur_id
 * @property string $kategori_id
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property KategoriBarang $kategori
 * @property StrukturBarang $struktur
 * @property User $updatedBy
 * @property SkuDetail[] $skuDetails
 */
class Sku extends CActiveRecord
{
    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF       = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sku';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['nomor, nama', 'required'],
            ['status', 'numerical', 'integerOnly' => true],
            ['nomor', 'length', 'max' => 30],
            ['nama', 'length', 'max' => 45],
            ['struktur_id, kategori_id, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, nomor, nama, struktur_id, kategori_id, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'kategori'   => [self::BELONGS_TO, 'KategoriBarang', 'kategori_id'],
            'struktur'   => [self::BELONGS_TO, 'StrukturBarang', 'struktur_id'],
            'updatedBy'  => [self::BELONGS_TO, 'User', 'updated_by'],
            'skuDetails' => [self::HAS_MANY, 'SkuDetail', 'sku_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'nomor'       => 'Nomor',
            'nama'        => 'Nama',
            'struktur_id' => 'Struktur',
            'kategori_id' => 'Kategori',
            'status'      => 'Status',
            'updated_at'  => 'Updated At',
            'updated_by'  => 'Updated By',
            'created_at'  => 'Created At',
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
        $criteria->compare('nomor', $this->nomor, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('struktur_id', $this->struktur_id, true);
        $criteria->compare('kategori_id', $this->kategori_id, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = [
            'defaultOrder' => 't.nama',
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
     * @return Sku the static model class
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
        if (empty($this->kategori_id)) {
            $this->kategori_id = null;
        }
        if (empty($this->struktur_id)) {
            $this->struktur_id = null;
        }
        return parent::beforeValidate();
    }

    public function getNamaStatus()
    {
        $status    = $this->filterStatus();
        $statusDef = [$status[self::STATUS_TIDAK_AKTIF], $status[self::STATUS_AKTIF]];
        return $statusDef[$this->status];
    }

    public function filterStatus()
    {
        return [
            self::STATUS_TIDAK_AKTIF => 'Non Aktif',
            self::STATUS_AKTIF       => 'Aktif',
        ];
    }

    public function getNamaStruktur()
    {
        $struktur = StrukturBarang::model()->findByPk($this->struktur_id);
        return is_null($struktur) ? "" : $struktur->getFullPath();
    }
}
