<?php

/**
 * This is the model class for table "sku_transfer_detail".
 *
 * The followings are the available columns in table 'sku_transfer_detail':
 * @property string $id
 * @property string $sku_transfer_id
 * @property string $from_barang_id
 * @property string $from_satuan_id
 * @property integer $from_qty
 * @property string $from_barcode
 * @property string $from_nama_barang
 * @property string $from_nama_satuan
 * @property string $to_barang_id
 * @property string $to_satuan_id
 * @property integer $to_qty
 * @property string $to_barcode
 * @property string $to_nama_barang
 * @property string $to_nama_satuan
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $fromBarang
 * @property BarangSatuan $fromSatuan
 * @property SkuTransfer $skuTransfer
 * @property Barang $toBarang
 * @property BarangSatuan $toSatuan
 * @property User $updatedBy
 */
class SkuTransferDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sku_transfer_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['sku_transfer_id, from_barang_id, from_satuan_id, from_qty, from_barcode, from_nama_barang, from_nama_satuan, to_barang_id, to_satuan_id, to_qty, to_barcode, to_nama_barang, to_nama_satuan', 'required'],
			['from_qty, to_qty', 'numerical', 'integerOnly' => true],
			['sku_transfer_id, from_barang_id, from_satuan_id, to_barang_id, to_satuan_id, updated_by', 'length', 'max' => 10],
			['from_barcode, to_barcode', 'length', 'max' => 30],
			['from_nama_barang, from_nama_satuan, to_nama_barang, to_nama_satuan', 'length', 'max' => 45],
			['created_at, updated_at, updated_by', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, sku_transfer_id, from_barang_id, from_satuan_id, from_qty, from_barcode, from_nama_barang, from_nama_satuan, to_barang_id, to_satuan_id, to_qty, to_barcode, to_nama_barang, to_nama_satuan, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
			'fromBarang'  => [self::BELONGS_TO, 'Barang', 'from_barang_id'],
			'fromSatuan'  => [self::BELONGS_TO, 'BarangSatuan', 'from_satuan_id'],
			'skuTransfer' => [self::BELONGS_TO, 'SkuTransfer', 'sku_transfer_id'],
			'toBarang'    => [self::BELONGS_TO, 'Barang', 'to_barang_id'],
			'toSatuan'    => [self::BELONGS_TO, 'BarangSatuan', 'to_satuan_id'],
			'updatedBy'   => [self::BELONGS_TO, 'User', 'updated_by'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'sku_transfer_id'  => 'Sku Transfer',
			'from_barang_id'   => 'From Barang',
			'from_satuan_id'   => 'From Satuan',
			'from_qty'         => 'From Qty',
			'from_barcode'     => 'From Barcode',
			'from_nama_barang' => 'From Nama Barang',
			'from_nama_satuan' => 'From Nama Satuan',
			'to_barang_id'     => 'To Barang',
			'to_satuan_id'     => 'To Satuan',
			'to_qty'           => 'To Qty',
			'to_barcode'       => 'To Barcode',
			'to_nama_barang'   => 'To Nama Barang',
			'to_nama_satuan'   => 'To Nama Satuan',
			'updated_at'       => 'Updated At',
			'updated_by'       => 'Updated By',
			'created_at'       => 'Created At',
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
		$criteria->compare('sku_transfer_id', $this->sku_transfer_id, true);
		$criteria->compare('from_barang_id', $this->from_barang_id, true);
		$criteria->compare('from_satuan_id', $this->from_satuan_id, true);
		$criteria->compare('from_qty', $this->from_qty);
		$criteria->compare('from_barcode', $this->from_barcode, true);
		$criteria->compare('from_nama_barang', $this->from_nama_barang, true);
		$criteria->compare('from_nama_satuan', $this->from_nama_satuan, true);
		$criteria->compare('to_barang_id', $this->to_barang_id, true);
		$criteria->compare('to_satuan_id', $this->to_satuan_id, true);
		$criteria->compare('to_qty', $this->to_qty);
		$criteria->compare('to_barcode', $this->to_barcode, true);
		$criteria->compare('to_nama_barang', $this->to_nama_barang, true);
		$criteria->compare('to_nama_satuan', $this->to_nama_satuan, true);
		$criteria->compare('updated_at', $this->updated_at, true);
		$criteria->compare('updated_by', $this->updated_by, true);
		$criteria->compare('created_at', $this->created_at, true);

		return new CActiveDataProvider($this, [
			'criteria' => $criteria,
		]);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SkuTransferDetail the static model class
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
}
