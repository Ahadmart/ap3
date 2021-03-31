<?php

/**
 * This is the model class for table "po_detail".
 *
 * The followings are the available columns in table 'po_detail':
 * @property string $id
 * @property string $po_id
 * @property string $barang_id
 * @property string $barcode
 * @property string $nama
 * @property string $harga_beli
 * @property string $harga_jual
 * @property double $ads
 * @property integer $stok
 * @property double $est_sisa_hari
 * @property string $restock_min
 * @property integer $saran_order
 * @property string $qty_order
 * @property string $tgl_jual_max
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Po $po
 * @property User $updatedBy
 */
class PoDetail extends CActiveRecord
{
    const STATUS_DRAFT = 0;
    const STATUS_ORDER = 10;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'po_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['po_id, barcode, nama, harga_beli', 'required'],
            ['stok, saran_order, status', 'numerical', 'integerOnly' => true],
            ['ads, est_sisa_hari', 'numerical'],
            ['po_id, barang_id, restock_min, qty_order, updated_by', 'length', 'max' => 10],
            ['barcode', 'length', 'max' => 30],
            ['nama', 'length', 'max' => 45],
            ['harga_beli, harga_jual', 'length', 'max' => 18],
            ['tgl_jual_max, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, po_id, barang_id, barcode, nama, harga_beli, harga_jual, ads, stok, est_sisa_hari, restock_min, saran_order, qty_order, tgl_jual_max, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
            'po'        => [self::BELONGS_TO, 'Po', 'po_id'],
            'updatedBy' => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'po_id'         => 'Po',
            'barang_id'     => 'Barang',
            'barcode'       => 'Barcode',
            'nama'          => 'Nama',
            'harga_beli'    => 'Harga Beli',
            'harga_jual'    => 'Harga Jual',
            'ads'           => 'Ads',
            'stok'          => 'Stok',
            'est_sisa_hari' => 'Est Sisa Hari',
            'restock_min'   => 'Minimum Restock',
            'saran_order'   => 'Saran Order',
            'qty_order'     => 'Qty Order',
            'tgl_jual_max'  => 'Tgl Penjualan Terakhir',
            'status'        => 'Status',
            'updated_at'    => 'Updated At',
            'updated_by'    => 'Updated By',
            'created_at'    => 'Created At',
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
    public function search($pageSize = 10)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('po_id', $this->po_id, true);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('barcode', $this->barcode, true);
        $criteria->compare('nama', $this->nama, true);
        $criteria->compare('harga_beli', $this->harga_beli, true);
        $criteria->compare('harga_jual', $this->harga_jual, true);
        $criteria->compare('ads', $this->ads);
        $criteria->compare('stok', $this->stok);
        $criteria->compare('est_sisa_hari', $this->est_sisa_hari);
        $criteria->compare('restock_min', $this->restock_min);
        $criteria->compare('saran_order', $this->saran_order);
        $criteria->compare('qty_order', $this->qty_order, true);
        $criteria->compare('tgl_jual_max', $this->tgl_jual_max, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $sort = [
            'defaultOrder' => 't.updated_at desc',
        ];

        return new CActiveDataProvider($this, [
            'criteria'   => $criteria,
            'sort'       => $sort,
            'pagination' => [
                'pageSize' => (int) $pageSize,
            ],
        ]);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param  string   $className active record class name.
     * @return PoDetail the static model class
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

    public function getTotal()
    {
        return number_format($this->qty_order * $this->harga_beli, 0, ',', '.');
    }

    public static function sudahAda($poId, $barangId)
    {
        return PoDetail::model()->find('po_id=:poId AND barang_id=:barangId', [':poId' => $poId, ':barangId' => $barangId]);
    }
}
