<?php

/**
 * This is the model class for table "sku_detail".
 *
 * The followings are the available columns in table 'sku_detail':
 * @property string $id
 * @property string $sku_id
 * @property string $barang_id
 * @property string $tier
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Sku $sku
 * @property User $updatedBy
 */
class SkuDetail extends CActiveRecord
{
	public $barcode;
	public $namaBarang;
	public $namaSatuan;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sku_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['sku_id, barang_id', 'required'],
			['sku_id, barang_id, tier, updated_by', 'length', 'max' => 10],
			['created_at, updated_at, updated_by', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, sku_id, barang_id, tier, updated_at, updated_by, created_at, barcode, namaBarang, namaSatuan', 'safe', 'on' => 'search'],
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
			'satuan'    => [self::BELONGS_TO, 'SatuanBarang', ['satuan_id' => 'id'], 'through' => 'barang'],
			'sku'       => [self::BELONGS_TO, 'Sku', 'sku_id'],
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
			'sku_id'     => 'Sku',
			'barang_id'  => 'Barang',
			'tier'       => 'Tier',
			'updated_at' => 'Updated At',
			'updated_by' => 'Updated By',
			'created_at' => 'Created At',
			'namaBarang' => 'Barang',
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
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, false);
		$criteria->compare('sku_id', $this->sku_id, false);
		$criteria->compare('barang_id', $this->barang_id, false);
		$criteria->compare('tier', $this->tier, false);
		$criteria->compare('updated_at', $this->updated_at, true);
		$criteria->compare('updated_by', $this->updated_by, true);
		$criteria->compare('created_at', $this->created_at, true);

		$criteria->with = ['barang', 'satuan'];
		$criteria->compare('barang.barcode', $this->barcode, true);
		$criteria->compare('barang.nama', $this->namaBarang, true);
		$criteria->compare('satuan.nama', $this->namaSatuan, true);

		$sort = [
			'defaultOrder' => 't.tier desc',
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
	 * @return SkuDetail the static model class
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
