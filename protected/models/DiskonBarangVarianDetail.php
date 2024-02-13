<?php

/**
 * This is the model class for table "barang_diskon_detail_varian".
 *
 * The followings are the available columns in table 'barang_diskon_detail_varian':
 * @property string $id
 * @property string $barang_diskon_id
 * @property integer $tipe
 * @property string $barang_id
 * @property string $nominal
 * @property double $persen
 * @property string $qty
 * @property string $qty_min
 * @property string $qty_max
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property BarangDiskon $barangDiskon
 * @property User $updatedBy
 */
class DiskonBarangVarianDetail extends CActiveRecord
{
	const TIPE_BARANG_DISKON  = 0;
	const TIPE_BARANG_BONUS = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'barang_diskon_detail_varian';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['barang_diskon_id, nominal', 'required'],
			['tipe', 'numerical', 'integerOnly' => true],
			['persen', 'numerical'],
			['barang_diskon_id, barang_id, qty, qty_min, qty_max, updated_by', 'length', 'max' => 10],
			['nominal', 'length', 'max' => 18],
			['created_at, updated_at, updated_by', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, barang_diskon_id, tipe, barang_id, nominal, persen, qty, qty_min, qty_max, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
			'barang'       => [self::BELONGS_TO, 'Barang', 'barang_id'],
			'barangDiskon' => [self::BELONGS_TO, 'BarangDiskon', 'barang_diskon_id'],
			'updatedBy'    => [self::BELONGS_TO, 'User', 'updated_by'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'barang_diskon_id' => 'Barang Diskon',
			'tipe'             => 'Tipe',
			'barang_id'        => 'Barang',
			'nominal'          => 'Nominal',
			'persen'           => 'Persen',
			'qty'              => 'Qty',
			'qty_min'          => 'Qty Min',
			'qty_max'          => 'Qty Max',
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
		$criteria->compare('barang_diskon_id', $this->barang_diskon_id, true);
		$criteria->compare('tipe', $this->tipe);
		$criteria->compare('barang_id', $this->barang_id, true);
		$criteria->compare('nominal', $this->nominal, true);
		$criteria->compare('persen', $this->persen);
		$criteria->compare('qty', $this->qty, true);
		$criteria->compare('qty_min', $this->qty_min, true);
		$criteria->compare('qty_max', $this->qty_max, true);
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
	 * @return DiskonBarangVarianDetail the static model class
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
