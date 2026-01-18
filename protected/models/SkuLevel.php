<?php

/**
 * This is the model class for table "sku_level".
 *
 * The followings are the available columns in table 'sku_level':
 * @property string $id
 * @property string $sku_id
 * @property string $level
 * @property string $satuan_id
 * @property string $rasio_konversi
 * @property string $jumlah_per_unit
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Sku $sku
 * @property BarangSatuan $satuan
 * @property User $updatedBy
 */
class SkuLevel extends CActiveRecord
{
    public $namaSatuan;
    public $maxLevel; // Variabel untuk menyimpan level maximum di sku tertentu

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sku_level';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['sku_id, satuan_id', 'required'],
            ['sku_id, level, satuan_id, rasio_konversi, jumlah_per_unit, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, sku_id, level, satuan_id, rasio_konversi, jumlah_per_unit, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'sku'       => [self::BELONGS_TO, 'Sku', 'sku_id'],
            'satuan'    => [self::BELONGS_TO, 'SatuanBarang', 'satuan_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'sku_id'          => 'Sku',
            'level'           => 'Level',
            'satuan_id'       => 'Satuan',
            'rasio_konversi'  => 'Rasio Konversi',
            'jumlah_per_unit' => 'Jml/Unit',
            'updated_at'      => 'Updated At',
            'updated_by'      => 'Updated By',
            'created_at'      => 'Created At',
            'namaSatuan'      => 'Satuan',
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
        $criteria->compare('sku_id', $this->sku_id, true);
        $criteria->compare('level', $this->level, true);
        $criteria->compare('satuan_id', $this->satuan_id, true);
        $criteria->compare('rasio_konversi', $this->rasio_konversi, true);
        $criteria->compare('jumlah_per_unit', $this->jumlah_per_unit, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = [
            'defaultOrder' => 'level desc',
            'attributes'   => [
                '*',
                'namaSatuan' => [
                    'asc'  => 'satuan.nama',
                    'desc' => 'satuan.nama desc',
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
     * @return SkuLevel the static model class
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
        $jumlahPerUnit = $this->rasio_konversi;
        if ($this->level >= 3) {
            for ($i = $this->level - 1; $i >= 1; $i--) {
                $skuLevel = SkuLevel::model()->find('sku_id = :skuId AND level = :level', [
                    ':skuId' => $this->sku_id,
                    ':level' => $i,
                ]);
                // Yii::log('Level: ' . $i . '; skuId: ' . $this->sku_id);
                // Yii::log(var_export($skuLevel, true));
                $jumlahPerUnit *= $skuLevel->rasio_konversi;
            }
        } else {
            $jumlahPerUnit = $this->rasio_konversi;
        }
        $this->jumlah_per_unit = $jumlahPerUnit;

        return parent::beforeValidate();
    }

    public static function kumulatifRasioKonversi($skuId, $levelAsal, $levelTujuan)
    {
        Yii::log("lvl Asal: {$levelAsal}; lvl Tujuan: {$levelTujuan}");
        if ($levelAsal < $levelTujuan) {
            $kumulatif = Yii::app()->db->createCommand()
                ->select('FLOOR(EXP(SUM(LOG(rasio_konversi + 1e-9)))) AS jumlah')
                ->from('sku_level')
                ->where('sku_id = :skuId AND level > :levelAsal AND level <= :levelTujuan', [
                    ':skuId'       => $skuId,
                    ':levelTujuan' => $levelTujuan,
                    ':levelAsal'   => $levelAsal,
                ])
                ->queryScalar();
            $pecahan = new Pecahan(1, $kumulatif);
            return $pecahan->toString();
        }
        return Yii::app()->db->createCommand()
            ->select('FLOOR(EXP(SUM(LOG(rasio_konversi + 1e-9)))) AS jumlah')
            ->from('sku_level')
            ->where('sku_id = :skuId AND level > :levelTujuan AND level <= :levelAsal', [
                ':skuId'       => $skuId,
                ':levelTujuan' => $levelTujuan,
                ':levelAsal'   => $levelAsal,
            ])
            ->queryScalar();
    }

    /**
     * Method syncJPUAtas
     * Menghitung ulang Jumlah Per Unit untuk level di atas $startLevel
     * @param int $skuId sku ID
     * @param int $startLevel Level mulai hitung ulang
     *
     * @return void
     */
    public static function syncJPUAtas($skuId, $startLevel)
    {
        if ($startLevel < 3) {
            // Nothing to do
            return true;
        }

        $skuLevels = SkuLevel::model()->findAll('sku_id=:id AND level >= :level', [
            ':id'    => $skuId,
            ':level' => $startLevel,
        ]);
        if (!empty($skuLevels)) {
            foreach ($skuLevels as $level) {
                $level->validate();
                if (!$level->hasErrors()) {
                    $level->save(false);
                }
            }
        }
    }
}
