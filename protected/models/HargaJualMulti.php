<?php

/**
 * This is the model class for table "barang_harga_jual_multi".
 *
 * The followings are the available columns in table 'barang_harga_jual_multi':
 * @property string $id
 * @property string $barang_id
 * @property string $satuan_id
 * @property string $qty
 * @property string $harga
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property BarangSatuan $satuan
 * @property User $updatedBy
 */
class HargaJualMulti extends CActiveRecord
{
    public $barcode;
    public $namaBarang;
    public $namaSatuan;
    public $hargaJual;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang_harga_jual_multi';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['barang_id, satuan_id, harga', 'required'],
            ['barang_id, satuan_id, qty, updated_by', 'length', 'max' => 10],
            ['harga', 'length', 'max' => 18],
            ['created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, barang_id, satuan_id, qty, harga, updated_at, updated_by, created_at, barcode, namaBarang, namaSatuan', 'safe', 'on' => 'search'],
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
            'barang'    => [self::BELONGS_TO, 'Barang', 'barang_id'],
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
            'id'         => 'ID',
            'barang_id'  => 'Barang',
            'satuan_id'  => 'Satuan',
            'qty'        => 'Isi',
            'harga'      => 'Harga @',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Sejak',
            'barcode'    => 'Barcode',
            'namaBarang' => 'Nama',
            'namaSatuan' => 'Satuan',
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
     *                             based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.barang_id', $this->barang_id);
        $criteria->compare('t.satuan_id', $this->satuan_id);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('harga', $this->harga, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['barang', 'satuan'];
        $criteria->compare('barang.barcode', $this->barcode, true);
        $criteria->compare('barang.nama', $this->namaBarang, true);
        $criteria->compare('barang.satuan_id', $this->namaSatuan, true);

        $criteria->join = 'join (select barang_id, max(id) max_id from barang_harga_jual_multi group by barang_id, qty) t1 on t1.max_id = t.id';

        $criteria->addCondition('harga > 0');

        $sort = [
            'defaultOrder' => 'barang.nama, t.qty',
            'attributes'   => [
                'barcode' => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc'
                ],
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc'
                ],
                'namaSatuan' => [
                    'asc'  => 'barang.satuan_id',
                    'desc' => 'barang.satuan_id desc'
                ],
                '*'
            ]
        ];

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'sort'    => $sort
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param  string         $className active record class name.
     * @return HargaJualMulti the static model class
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
        $this->updated_at = null; // Trigger current timestamp
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public static function updateHarga($barangId, $attributes)
    {
        $return = false;
        // Cari harga jual multi terakhir
        $hasil = Yii::app()->db->createCommand()
            ->select('satuan_id, harga')
            ->from(HargaJualMulti::model()->tableName())
            ->where('barang_id = :barangId AND qty = :qty', [
                ':barangId' => $barangId,
                ':qty'      => (int) $attributes['qty']
            ])
            ->order('id desc')
            ->queryRow();

        if (empty($hasil) || $hasil['harga'] != $attributes['harga'] || $hasil['satuan_id'] != $attributes['satuan_id']) {
            // Jika tidak sama atau belum ada maka: insert harga jual baru
            $hargaJualModel             = new HargaJualMulti;
            $hargaJualModel->attributes = $attributes;
            $hargaJualModel->barang_id  = $barangId;
            if ($hargaJualModel->save()) {
                $return = true;
            }
        } else {
            $return = true;
        }
        return $return;
    }

    public static function updateHargaTrx($barangId, $attributes)
    {
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if (self::updateHarga($barangId, $attributes)) {
                $transaction->commit();
                return true;
            } else {
                throw new Exception('Gagal Update Multi Harga Jual');
            }
        } catch (Exception $e) {
            $transaction->rollback();
            throw $e;
        }
    }

    public function afterFind()
    {
        $this->harga      = number_format($this->harga, 0, ',', '.');
        $this->created_at = isset($this->created_at) ? date_format(date_create_from_format('Y-m-d H:i:s', $this->created_at), 'd-m-Y H:i:s') : '';
        return parent::afterFind();
    }

    /**
     * Daftar Multi Harga Jual yang sedang aktif
     *
     * @param  int   $barangId ID Barang
     * @return array [nama_satuan, qty, harga]
     */
    public static function listAktif($barangId, $sort = '')
    {
        $sql = '
        SELECT 
            s.nama nama_satuan, qty, harga
        FROM
            barang_harga_jual_multi t
                JOIN
            barang_satuan s ON s.id = t.satuan_id
        WHERE
            t.id IN (SELECT 
                    MAX(id) id
                FROM
                    barang_harga_jual_multi
                WHERE
                    barang_id = :barangId
                GROUP BY qty)
                AND harga > 0
        ORDER BY qty ' . $sort;

        return Yii::app()->db->createCommand($sql)
            ->bindValue(':barangId', $barangId)
            ->queryAll();
    }
}
