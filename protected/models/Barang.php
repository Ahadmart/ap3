<?php

/**
 * This is the model class for table "barang".
 *
 * The followings are the available columns in table 'barang':
 * @property string $id
 * @property string $barcode
 * @property string $nama
 * @property string $kategori_id
 * @property string $satuan_id
 * @property string $rak_id
 * @property string $restock_point
 * @property string $restock_level
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property BarangKategori $kategori
 * @property BarangSatuan $satuan
 * @property BarangRak $rak
 * @property User $updatedBy
 * @property BarangHargaJual[] $barangHargaJuals
 * @property BarangHargaJualRekomendasi[] $barangHargaJualRekomendasis
 * @property InventoryBalance[] $inventoryBalances
 * @property PembelianDetail[] $pembelianDetails
 * @property PenjualanDetail[] $penjualanDetails
 * @property StockOpnameDetail[] $stockOpnameDetails
 * @property SupplierBarang[] $supplierBarangs
 */
class Barang extends CActiveRecord
{

    const STATUS_TIDAK_AKTIF = 0;
    const STATUS_AKTIF = 1;

    public $soId;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang';
    }

    public function scopes()
    {
        return array(
            'belumSO',
            'aktif' => array(
                'condition' => 'status = ' . self::STATUS_AKTIF
            )
        );
    }

    public function belumSO($soId, $rakId)
    {
        $this->getDbCriteria()->mergeWith(array(
            'join' => "left join stock_opname_detail sod on t.id = sod.barang_id and sod.stock_opname_id = {$soId}",
            'condition' => "t.rak_id={$rakId} and sod.barang_id is null"
        ));
        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('barcode, nama, kategori_id, satuan_id', 'required'),
            array('barcode', 'unique'),
            array('status', 'numerical', 'integerOnly' => true),
            array('barcode', 'length', 'max' => 30),
            array('nama', 'length', 'max' => 45),
            array('kategori_id, satuan_id, rak_id, restock_point, restock_level, updated_by', 'length', 'max' => 10),
            array('created_at, updated_at, updated_by', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, barcode, nama, kategori_id, satuan_id, rak_id, restock_point, restock_level, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'kategori' => array(self::BELONGS_TO, 'KategoriBarang', 'kategori_id'),
            'satuan' => array(self::BELONGS_TO, 'SatuanBarang', 'satuan_id'),
            'rak' => array(self::BELONGS_TO, 'RakBarang', 'rak_id'),
            'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
            'barangHargaJuals' => array(self::HAS_MANY, 'BarangHargaJual', 'barang_id'),
            'barangHargaJualRekomendasis' => array(self::HAS_MANY, 'BarangHargaJualRekomendasi', 'barang_id'),
            'inventoryBalances' => array(self::HAS_MANY, 'InventoryBalance', 'barang_id'),
            'pembelianDetails' => array(self::HAS_MANY, 'PembelianDetail', 'barang_id'),
            'penjualanDetails' => array(self::HAS_MANY, 'PenjualanDetail', 'barang_id'),
            'stockOpnameDetails' => array(self::HAS_MANY, 'StockOpnameDetail', 'barang_id'),
            'supplierBarangs' => array(self::HAS_MANY, 'SupplierBarang', 'barang_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'barcode' => 'Barcode',
            'nama' => 'Nama',
            'kategori_id' => 'Kategori',
            'satuan_id' => 'Satuan',
            'rak_id' => 'Rak',
            'restock_point' => 'Restock Point',
            'restock_level' => 'Restock Level',
            'status' => 'Status',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At'
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('barcode', $this->barcode, true);
        $criteria->compare('t.nama', $this->nama, true);
        $criteria->compare('kategori_id', $this->kategori_id);
        $criteria->compare('satuan_id', $this->satuan_id);
        $criteria->compare('restock_point', $this->restock_point, true);
        $criteria->compare('restock_level', $this->restock_level, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('t.created_at', $this->created_at, true);
        if ($this->rak_id != 'NULL') {
            $criteria->compare('rak_id', $this->rak_id);
        } else {
            $criteria->addCondition('rak_id IS NULL');
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Barang the static model class
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
        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    public function beforeValidate()
    {
        if (empty($this->rak_id)) {
            $this->rak_id = NULL;
        }
        return parent::beforeValidate();
    }

    public function getNamaStatus()
    {
        $statusDef = array('Non Aktif', 'Aktif');
        return $statusDef[$this->status];
    }

    public function getStok()
    {
        $stok = Yii::app()->db->createCommand("
				  select sum(qty) stok
				  from inventory_balance
				  where barang_id = {$this->id}
				  ")->queryRow();
        return $stok['stok'] ? $stok['stok'] : 0;
    }

    public function getHargaJualRaw()
    {
        $hasil = Yii::app()->db->createCommand("
					select harga
					from " . HargaJual::model()->tableName() . "
					where barang_id = {$this->id}
					order by id desc
					limit 1
			  ")->queryRow();
        return $hasil['harga'];
    }

    public function getHargaJual()
    {
        return number_format($this->getHargaJualRaw(), 0, ',', '.');
    }

    public function filterStatus()
    {
        return array(
            Barang::STATUS_TIDAK_AKTIF => 'Non Aktif',
            Barang::STATUS_AKTIF => 'Aktif'
        );
    }

    public function filterKategori()
    {
        return CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function filterSatuan()
    {
        return CHtml::listData(SatuanBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

    public function filterRak()
    {
        return ['NULL' => 'NULL'] + CHtml::listData(RakBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama');
    }

}
