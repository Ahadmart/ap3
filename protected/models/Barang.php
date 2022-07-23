<?php

/**
 * This is the model class for table "barang".
 *
 * The followings are the available columns in table 'barang':
 * @property string $id
 * @property string $barcode
 * @property string $nama
 * @property string $struktur_id
 * @property string $kategori_id
 * @property string $satuan_id
 * @property string $rak_id
 * @property string $restock_point
 * @property string $restock_level
 * @property string $restock_min
 * @property string $variant_coefficient
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property StrukturBarang $struktur
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
    const STATUS_AKTIF       = 1;

    public $soId;
    public $daftarSupplier;
    public $strukturFullPath;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'barang';
    }

    public function scopes()
    {
        return [
            'belumSO',
            'aktif' => [
                'condition' => 'status = ' . self::STATUS_AKTIF,
            ],
        ];
    }

    public function belumSO($soId, $rakId)
    {
        $this->getDbCriteria()->mergeWith([
            'join'      => "left join stock_opname_detail sod on t.id = sod.barang_id and sod.stock_opname_id = {$soId}",
            'condition' => "t.rak_id={$rakId} and sod.barang_id is null",
        ]);
        return $this;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $import = [
            ['barcode, nama, satuan_id, struktur_id', 'required', 'message' => '{attribute} harus diisi!'],
            ['barcode', 'unique'],
            ['status', 'numerical', 'integerOnly' => true],
            ['barcode', 'length', 'max' => 30],
            ['nama', 'length', 'max' => 45],
            ['kategori_id, satuan_id, restock_point, restock_level, restock_min, updated_by', 'length', 'max' => 10],
            ['kategori_id, rak_id, created_at, updated_at, updated_by', 'safe'],
        ];
        $default = [
            ['barcode, nama, satuan_id, struktur_id, rak_id', 'required', 'message' => '{attribute} harus diisi!'],
            ['barcode', 'unique'],
            ['status', 'numerical', 'integerOnly' => true],
            ['barcode', 'length', 'max' => 30],
            ['nama', 'length', 'max' => 45],
            ['kategori_id, satuan_id, restock_point, restock_level, restock_min, updated_by', 'length', 'max' => 10],
            ['kategori_id, rak_id, created_at, updated_at, updated_by', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, barcode, nama, struktur_id, kategori_id, satuan_id, rak_id, restock_point, restock_level, restock_min, variant_coefficient, status, daftarSupplier, strukturFullPath', 'safe', 'on' => 'search'],
        ];
        if ($this->scenario == 'import') {
            return $import;
        } else {
            return $default;
        }
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
            'kategori'                    => [self::BELONGS_TO, 'KategoriBarang', 'kategori_id'],
            'struktur'                    => [self::BELONGS_TO, 'StrukturBarang', 'struktur_id'],
            'satuan'                      => [self::BELONGS_TO, 'SatuanBarang', 'satuan_id'],
            'rak'                         => [self::BELONGS_TO, 'RakBarang', 'rak_id'],
            'updatedBy'                   => [self::BELONGS_TO, 'User', 'updated_by'],
            'barangHargaJuals'            => [self::HAS_MANY, 'BarangHargaJual', 'barang_id'],
            'barangHargaJualRekomendasis' => [self::HAS_MANY, 'BarangHargaJualRekomendasi', 'barang_id'],
            'inventoryBalances'           => [self::HAS_MANY, 'InventoryBalance', 'barang_id'],
            'pembelianDetails'            => [self::HAS_MANY, 'PembelianDetail', 'barang_id'],
            'penjualanDetails'            => [self::HAS_MANY, 'PenjualanDetail', 'barang_id'],
            'stockOpnameDetails'          => [self::HAS_MANY, 'StockOpnameDetail', 'barang_id'],
            'supplierBarangs'             => [self::HAS_MANY, 'SupplierBarang', 'barang_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'                  => 'ID',
            'barcode'             => 'Barcode',
            'nama'                => 'Nama',
            'struktur_id'         => 'Struktur',
            'kategori_id'         => 'Kategori',
            'satuan_id'           => 'Satuan',
            'rak_id'              => 'Rak',
            'restock_point'       => 'Restock Point',
            'restock_level'       => 'Restock Level',
            'restock_min'         => 'Minimum Restock',
            'variant_coefficient' => 'VC',
            'status'              => 'Status',
            'updated_at'          => 'Updated At',
            'updated_by'          => 'Updated By',
            'created_at'          => 'Created At',
            'daftarSupplier'      => 'Supplier',
            'strukturFullPath'    => 'Struktur',
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
    public function search($pageSize = 10, $merge = null)
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('barcode', $this->barcode, true);
        $criteria->compare('t.nama', $this->nama, true);
        $criteria->compare('struktur_id', $this->struktur_id);
        $criteria->compare('kategori_id', $this->kategori_id);
        $criteria->compare('satuan_id', $this->satuan_id);
        $criteria->compare('restock_point', $this->restock_point, true);
        $criteria->compare('restock_level', $this->restock_level, true);
        $criteria->compare('restock_min', $this->restock_min, true);
        $criteria->compare('variant_coefficient', $this->variant_coefficient, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('t.created_at', $this->created_at, true);
        $criteria->compare("(SELECT
                                group_concat(p.nama)
                            FROM
                                supplier_barang sb
                                    JOIN
                                profil p ON p.id = sb.supplier_id
                            WHERE
                                barang_id = t.id
                                GROUP BY barang_id)", $this->daftarSupplier, true);
        $criteria->compare("(
            SELECT
                CONCAT(lv1.nama, ' > ', lv2.nama, ' > ', lv3.nama)
            FROM
                barang b
                    JOIN
                barang_struktur lv3 ON lv3.id = b.struktur_id
                    JOIN
                barang_struktur lv2 ON lv2.id = lv3.parent_id
                    JOIN
                barang_struktur lv1 ON lv1.id = lv2.parent_id
            WHERE
                b.id = t.id
            )
                ", $this->strukturFullPath, true);
        //        if (!empty($this->daftarSupplier)){
        //            $criteria->addCondition("");
        //        }
        if ($this->rak_id != 'NULL') {
            $criteria->compare('rak_id', $this->rak_id);
        } else {
            $criteria->addCondition('rak_id IS NULL');
        }
        if ($merge !== null) {
            $criteria->mergeWith($merge);
        }

        return new CActiveDataProvider($this, [
            'criteria'   => $criteria,
            'pagination' => [
                'pageSize' => $pageSize,
            ],
        ]);
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
            $this->rak_id = null;
        }
        if (empty($this->kategori_id)) {
            $this->kategori_id = null;
        }
        if (empty($this->struktur_id)) {
            $this->struktur_id = null;
        }
        if (empty($this->restock_min)) {
            $this->restock_min = 0;
        }
        return parent::beforeValidate();
    }

    public function getNamaStatus()
    {
        $statusDef = ['Non Aktif', 'Aktif'];
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
        return !empty($hasil) ? $hasil['harga'] : 0;
    }

    public function getHargaJual()
    {
        return number_format($this->getHargaJualRaw(), 0, ',', '.');
    }

    public function getHargaBeliRaw()
    {
        $hasil = Yii::app()->db->createCommand("
					select harga_beli
					from " . InventoryBalance::model()->tableName() . "
					where barang_id = {$this->id}
					order by id desc
					limit 1
			  ")->queryRow();
        return $hasil['harga_beli'];
    }

    public function getHargaBeli()
    {
        return number_format($this->getHargaBeliRaw(), 0, ',', '.');
    }

    public function filterStatus()
    {
        return [
            Barang::STATUS_TIDAK_AKTIF => 'Non Aktif',
            Barang::STATUS_AKTIF       => 'Aktif',
        ];
    }

    public function filterKategori()
    {
        return CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterSatuan()
    {
        return CHtml::listData(SatuanBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    public function filterRak()
    {
        return ['NULL' => 'NULL'] + CHtml::listData(RakBarang::model()->findAll(['order' => 'nama']), 'id', 'nama');
    }

    /**
     *
     * @return array Daftar Tag
     */
    public function getTagList()
    {
        return TagBarang::model()->findAll('barang_id=:barangId', [':barangId' => $this->id]);
    }

    /**
     * Mencari tanggal terakhir dari pembelian barang ini
     * @return Tanggal 'd-m-Y H:i:s'
     */
    public function getTanggalBeliTerakhir()
    {
        $hasil = Yii::app()->db->createCommand("
            SELECT
                DATE_FORMAT(pembelian.tanggal, '%d-%m-%Y %H:%i:%s') tanggal_terakhir
            FROM
                pembelian_detail
            JOIN
                pembelian on pembelian.id = pembelian_detail.pembelian_id
            WHERE
                barang_id =  {$this->id}
            ORDER BY pembelian_detail.id DESC
            LIMIT 1
	")->queryRow();
        return empty($hasil) ? NULL : $hasil['tanggal_terakhir'];
    }

    /**
     * Ambil daftar supplier dari barang ini
     * @return array list of supplier (id, nama, default)
     */
    public function getListSupplier()
    {
        $sql = "
            SELECT
                p.id, p.nama, sb.`default`
            FROM
                supplier_barang sb
                    JOIN
                profil p ON p.id = sb.supplier_id
            WHERE
                barang_id = :barangId
            ORDER BY sb.`default` DESC , p.nama
               ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValue(":barangId", $this->id);
        return $command->queryAll();
    }

    public function getNamaStruktur()
    {
        $struktur = StrukturBarang::model()->findByPk($this->struktur_id);
        return is_null($struktur) ? "" : $struktur->getFullPath();
    }

    public function getQtyReturBeliPosted()
    {
        $sql = "
        SELECT DISTINCT
            ib.barang_id, SUM(d.qty) qty_retur
        FROM
            retur_pembelian_detail d
                JOIN
            retur_pembelian rb ON rb.id = d.retur_pembelian_id
                AND rb.status = :statusRBPosted
                JOIN
            inventory_balance ib ON ib.id = d.inventory_balance_id
                AND ib.barang_id = :barangId
        GROUP BY ib.barang_id
        ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindValues([
            ':barangId' => $this->id,
            ':statusRBPosted' => ReturPembelian::STATUS_POSTED
        ]);
        $r = $command->queryRow();
        return !empty($r) ? $r['qty_retur'] : '';
    }
}
