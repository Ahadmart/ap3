<?php

/**
 * This is the model class for table "po_analisapls_param".
 *
 * The followings are the available columns in table 'po_analisapls_param':
 * @property string $id
 * @property string $po_id
 * @property string $range
 * @property string $order_period
 * @property string $lead_time
 * @property string $ssd
 * @property string $rak_id
 * @property string $struktur_lv1
 * @property string $struktur_lv2
 * @property string $struktur_lv3
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Po $po
 * @property BarangRak $rak
 * @property BarangStruktur $strukturLv1
 * @property BarangStruktur $strukturLv2
 * @property BarangStruktur $strukturLv3
 * @property User $updatedBy
 */
class PoAnalisaplsParam extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'po_analisapls_param';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['po_id, range, order_period, lead_time, ssd', 'required'],
			['status', 'numerical', 'integerOnly' => true],
			['po_id, range, order_period, lead_time, ssd, rak_id, struktur_lv1, struktur_lv2, struktur_lv3, updated_by', 'length', 'max' => 10],
			['created_at, updated_at, updated_by', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, po_id, range, order_period, lead_time, ssd, rak_id, struktur_lv1, struktur_lv2, struktur_lv3, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
			'po'          => [self::BELONGS_TO, 'Po', 'po_id'],
			'rak'         => [self::BELONGS_TO, 'RakBarang', 'rak_id'],
			'strukturLv1' => [self::BELONGS_TO, 'StrukturBarang', 'struktur_lv1'],
			'strukturLv2' => [self::BELONGS_TO, 'StrukturBarang', 'struktur_lv2'],
			'strukturLv3' => [self::BELONGS_TO, 'StrukturBarang', 'struktur_lv3'],
			'updatedBy'   => [self::BELONGS_TO, 'User', 'updated_by'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'po_id'        => 'PO',
			'range'        => 'Range Penjualan',
			'order_period' => 'Order Period',
			'lead_time'    => 'Lead Time',
			'ssd'          => 'Safety Stock Day',
			'rak_id'       => 'Rak',
			'struktur_lv1' => 'Struktur Lv1',
			'struktur_lv2' => 'Struktur Lv2',
			'struktur_lv3' => 'Struktur Lv3',
			'status'       => 'Status',
			'updated_at'   => 'Updated At',
			'updated_by'   => 'Updated By',
			'created_at'   => 'Created At',
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
		$criteria->compare('po_id', $this->po_id, true);
		$criteria->compare('range', $this->range, true);
		$criteria->compare('order_period', $this->order_period, true);
		$criteria->compare('lead_time', $this->lead_time, true);
		$criteria->compare('ssd', $this->ssd, true);
		$criteria->compare('rak_id', $this->rak_id, true);
		$criteria->compare('struktur_lv1', $this->struktur_lv1, true);
		$criteria->compare('struktur_lv2', $this->struktur_lv2, true);
		$criteria->compare('struktur_lv3', $this->struktur_lv3, true);
		$criteria->compare('status', $this->status);
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
	 * @return PoAnalisaplsParam the static model class
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
