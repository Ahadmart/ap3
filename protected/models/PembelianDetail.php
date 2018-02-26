<?php

/**
 * This is the model class for table "pembelian_detail".
 *
 * The followings are the available columns in table 'pembelian_detail':
 * @property string $id
 * @property string $pembelian_id
 * @property string $barang_id
 * @property string $qty
 * @property string $harga_beli
 * @property string $harga_jual
 * @property string $harga_jual_rekomendasi
 * @property string $tanggal_kadaluwarsa
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Pembelian $pembelian
 * @property User $updatedBy
 * @property ReturPembelianDetail[] $returPembelianDetails
 */
class PembelianDetail extends CActiveRecord
{
    public $barcode;
    public $namaBarang;
    public $subTotal;
    public $pembelianStatus;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pembelian_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['pembelian_id, barang_id, harga_beli, harga_jual', 'required'],
            ['pembelian_id, barang_id, qty, updated_by', 'length', 'max' => 10],
            ['harga_beli, harga_jual, harga_jual_rekomendasi', 'length', 'max' => 18],
            ['tanggal_kadaluwarsa, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, pembelian_id, barang_id, qty, harga_beli, harga_jual, harga_jual_rekomendasi, tanggal_kadaluwarsa, updated_at, updated_by, created_at, barcode, namaBarang, subTotal, pembelianStatus', 'safe', 'on' => 'search'],
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
            'barang'                => [self::BELONGS_TO, 'Barang', 'barang_id'],
            'pembelian'             => [self::BELONGS_TO, 'Pembelian', 'pembelian_id'],
            'updatedBy'             => [self::BELONGS_TO, 'User', 'updated_by'],
            'returPembelianDetails' => [self::HAS_MANY, 'ReturPembelianDetail', 'pembelian_detail_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                     => 'ID',
            'pembelian_id'           => 'Pembelian',
            'barang_id'              => 'Barang',
            'qty'                    => 'Qty',
            'harga_beli'             => 'Harga Beli',
            'harga_jual'             => 'Harga Jual',
            'harga_jual_rekomendasi' => 'RRP',
            'tanggal_kadaluwarsa'    => 'Tanggal Kadaluwarsa',
            'updated_at'             => 'Updated At',
            'updated_by'             => 'Updated By',
            'created_at'             => 'Created At',
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
    public function search($defaultOrder = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria();

        $criteria->compare('id', $this->id, true);
        $criteria->compare('pembelian_id', $this->pembelian_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('qty', $this->qty, true);
        $criteria->compare('harga_beli', $this->harga_beli, true);
        $criteria->compare('harga_jual', $this->harga_jual, true);
        $criteria->compare('harga_jual_rekomendasi', $this->harga_jual_rekomendasi, true);
        $criteria->compare('tanggal_kadaluwarsa', $this->tanggal_kadaluwarsa, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['barang', 'pembelian'];
        $criteria->compare('barang.barcode', $this->barcode, true);
        $criteria->compare('barang.nama', $this->namaBarang, true);
        $criteria->compare('pembelian.status', $this->pembelianStatus);

        $orderBy = is_null($defaultOrder) ? 't.id desc' : $defaultOrder;

        $sort = [
            'defaultOrder' => $orderBy,
            'attributes'   => [
                'barcode' => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc'
                ],
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc'
                ],
                '*',
            ]
        ];

        $pagination = [
            'pageSize' => 50
        ];

        return new CActiveDataProvider($this, [
            'criteria'   => $criteria,
            'sort'       => $sort,
            'pagination' => $pagination
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PembelianDetail the static model class
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

    public function getTotal()
    {
        return number_format($this->qty * $this->harga_beli, 0, ',', '.');
    }

    /**
     * Mengecek apakah barang di pembelian adalah baru baru diinput
     * @return bool true jika iya
     */
    public function isBarangBaru()
    {
        $ketemu = Yii::app()->db->createCommand()->
                select('barang.id')->
                from('pembelian_detail detail')->
                join('pembelian', 'detail.pembelian_id = pembelian.id')->
                join('barang', 'detail.barang_id = barang.id')->
                where('barang.created_at >= pembelian.created_at AND pembelian.id = :pembelianId AND barang.id = :barangId', [
                    ':pembelianId' => $this->pembelian_id,
                    ':barangId'    => $this->barang_id
                ])->
                queryRow();

        return $ketemu != false;
    }

    /**
     * Mengecek apakah barang di pembelian harga jualnya berubah
     * @return bool true jika iya
     */
    public function isHargaJualBerubah()
    {
        $ketemu = Yii::app()->db->createCommand()->
                select('detail.barang_id')->
                from('pembelian_detail detail')->
                join('pembelian', 'detail.pembelian_id = pembelian.id')->
                join('(SELECT
                            barang_id, harga
                        FROM
                            barang_harga_jual hj
                        WHERE
                            hj.barang_id = :barangId
                        ORDER BY id DESC
                        LIMIT 1) thj', 'thj.barang_id = detail.barang_id')->
                where('pembelian.id = :pembelianId AND detail.barang_id = :barangId AND detail.harga_jual != thj.harga', [
                    ':pembelianId' => $this->pembelian_id,
                    ':barangId'    => $this->barang_id
                ])->
                queryRow();

        return $ketemu != false;
    }

    /**
     * Mengecek apakah barang di pembelian marginnya (selisih harga jual dengan harga beli) minus atau 0 (nol)
     * @return boolean true jika iya
     */
    public function isMarginMin()
    {
        return $this->harga_jual - $this->harga_beli <= 0;
    }

    public function beforeValidate()
    {
        $this->tanggal_kadaluwarsa    = $this->tanggal_kadaluwarsa == '' ? null : $this->tanggal_kadaluwarsa;
        $this->harga_jual_rekomendasi = $this->harga_jual_rekomendasi == '' ? null : $this->harga_jual_rekomendasi;
        return parent::beforeValidate();
    }

    public function getStok()
    {
        $stok = Yii::app()->db->createCommand("
				  select sum(qty) stok
				  from inventory_balance
				  where barang_id = {$this->barang_id}
				  ")->queryRow();
        return $stok['stok'] ? $stok['stok'] : 0;
    }
}
