<?php

/**
 * This is the model class for table "stock_opname_detail".
 *
 * The followings are the available columns in table 'stock_opname_detail':
 * @property string $id
 * @property string $stock_opname_id
 * @property string $barang_id
 * @property integer $qty_tercatat
 * @property integer $qty_sebenarnya
 * @property integer $set_inaktif
 * @property string $ganti_rak_id
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property InventoryBalance[] $inventoryBalances
 * @property Barang $barang
 * @property StockOpname $stockOpname
 * @property User $updatedBy
 */
class StockOpnameDetail extends CActiveRecord
{

    public $barcode;
    public $namaBarang;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'stock_opname_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['stock_opname_id, barang_id, qty_tercatat, qty_sebenarnya', 'required', 'message' => '{attribute} harus diisi!'],
            ['qty_tercatat, qty_sebenarnya, set_inaktif', 'numerical', 'integerOnly' => true],
            ['stock_opname_id, barang_id, ganti_rak_id, updated_by', 'length', 'max' => 10],
            ['created_at, updated_at, updated_by, ganti_rak_id', 'safe'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, stock_opname_id, barang_id, qty_tercatat, qty_sebenarnya, set_inaktif, ganti_rak_id, updated_at, updated_by, created_at, barcode, namaBarang', 'safe', 'on' => 'search'],
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
            'inventoryBalances' => [self::HAS_MANY, 'InventoryBalance', 'stock_opname_detail_id'],
            'barang'            => [self::BELONGS_TO, 'Barang', 'barang_id'],
            'gantiRak'          => array(self::BELONGS_TO, 'BarangRak', 'ganti_rak_id'),
            'stockOpname'       => [self::BELONGS_TO, 'StockOpname', 'stock_opname_id'],
            'updatedBy'         => [self::BELONGS_TO, 'User', 'updated_by'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'stock_opname_id' => 'Stock Opname',
            'barang_id'       => 'Barang',
            'qty_tercatat'    => 'Qty',
            'qty_sebenarnya'  => 'Qty Asli',
            'set_inaktif'     => 'Set Inaktif',
            'ganti_rak_id'    => 'Ganti Rak',
            'updated_at'      => 'Updated At',
            'updated_by'      => 'Updated By',
            'created_at'      => 'Created At',
            'barcode'         => 'Barcode',
            'namaBarang'      => 'Nama',
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
        $criteria->compare('stock_opname_id', $this->stock_opname_id);
        $criteria->compare('barang_id', $this->barang_id, true);
        $criteria->compare('qty_tercatat', $this->qty_tercatat);
        $criteria->compare('qty_sebenarnya', $this->qty_sebenarnya);
        $criteria->compare('set_inaktif', $this->set_inaktif);
        $criteria->compare('ganti_rak_id', $this->ganti_rak_id, true);
        $criteria->compare('updated_at', $this->updated_at, true);
        $criteria->compare('updated_by', $this->updated_by, true);
        $criteria->compare('created_at', $this->created_at, true);

        $criteria->with = ['barang'];
        $criteria->compare('barang.barcode', $this->barcode, true);
        $criteria->compare('barang.nama', $this->namaBarang, true);

        $sort = [
            'defaultOrder' => 't.id desc',
            'attributes'   => [
                '*',
                'barcode'    => [
                    'asc'  => 'barang.barcode',
                    'desc' => 'barang.barcode desc',
                ],
                'namaBarang' => [
                    'asc'  => 'barang.nama',
                    'desc' => 'barang.nama desc',
                ],
            ],
        ];

        return new CActiveDataProvider(
            $this,
            [
                'criteria' => $criteria,
                'sort'     => $sort,
            ]
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return StockOpnameDetail the static model class
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

        /* Ini sengaja. Assignment in condition */
        if ($soDetail = $this->_sudahAda()) {
            $this->_tambahkanQty($soDetail);
        }

        $this->updated_at = date("Y-m-d H:i:s");
        $this->updated_by = Yii::app()->user->id;
        return parent::beforeSave();
    }

    private function _tambahkanQty($oldDetail)
    {
        if ($this->stockOpname->input_selisih) {
            /* Yang diinput adalah selisihnya */
            $this->qty_sebenarnya += $oldDetail->qty_sebenarnya - $oldDetail->qty_tercatat;
        } else {
            /* Yang diinput qty sebenarnya */
            $this->qty_sebenarnya += $oldDetail->qty_sebenarnya;
        }
        $oldDetail->delete();
    }

    private function _sudahAda()
    {
        $soDetail = StockOpnameDetail::model()->find(
            'barang_id=:barangId AND stock_opname_id=:soId',
            [':barangId' => $this->barang_id, ':soId' => $this->stock_opname_id]
        );
        return is_null($soDetail) ? false : $soDetail;
    }

    /**
     * Mencari jumlah barang untuk barangId yang sudah ada di soId
     * @param int $soId ID Stock Opname
     * @param int $barangId ID Barang
     * @return int Quantiti yang sudah di so di soId ini
     */
    public function qtyYangSudahSo($soId, $barangId)
    {
        $detail = Yii::app()->db->createCommand()
            ->select('sum(qty_sebenarnya) total')
            ->from(StockOpnameDetail::model()->tableName() . ' detail')
            ->where('stock_opname_id=:soId AND barang_id=:barangId', [':soId' => $soId, ':barangId' => $barangId])
            ->queryRow();
        return $detail ? $detail['total'] : 0;
    }

    public function getSelisih()
    {
        return $this->qty_sebenarnya - $this->qty_tercatat;
    }

    public function bedaRaknya()
    {
        $so = StockOpname::model()->findByPk($this->stock_opname_id);
        return $so->rak_id != $this->barang->rak_id;
    }
}
