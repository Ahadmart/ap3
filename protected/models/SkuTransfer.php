<?php

/**
 * This is the model class for table "sku_transfer".
 *
 * The followings are the available columns in table 'sku_transfer':
 * @property string $id
 * @property string $tanggal
 * @property string $nomor
 * @property string $referensi
 * @property string $tanggal_referensi
 * @property string $sku_id
 * @property string $keterangan
 * @property integer $status
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 * @property SkuTransferDetail[] $skuTransferDetails
 */
class SkuTransfer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sku_transfer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return [
			['tanggal', 'required'],
			['status', 'numerical', 'integerOnly' => true],
			['nomor, referensi', 'length', 'max' => 45],
			['sku_id, updated_by', 'length', 'max' => 10],
			['keterangan', 'length', 'max' => 500],
			['tanggal_referensi, created_at, updated_at, updated_by', 'safe'],
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			['id, tanggal, nomor, referensi, tanggal_referensi, sku_id, keterangan, status, updated_at, updated_by, created_at', 'safe', 'on' => 'search'],
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
			'updatedBy'          => [self::BELONGS_TO, 'User', 'updated_by'],
			'skuTransferDetails' => [self::HAS_MANY, 'SkuTransferDetail', 'sku_transfer_id'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id'                => 'ID',
			'tanggal'           => 'Tanggal',
			'nomor'             => 'Nomor',
			'referensi'         => 'Referensi',
			'tanggal_referensi' => 'Tanggal Referensi',
			'sku_id'            => 'Sku',
			'keterangan'        => 'Keterangan',
			'status'            => 'Status',
			'updated_at'        => 'Updated At',
			'updated_by'        => 'Updated By',
			'created_at'        => 'Created At',
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
		$criteria->compare('tanggal', $this->tanggal, true);
		$criteria->compare('nomor', $this->nomor, true);
		$criteria->compare('referensi', $this->referensi, true);
		$criteria->compare('tanggal_referensi', $this->tanggal_referensi, true);
		$criteria->compare('sku_id', $this->sku_id, true);
		$criteria->compare('keterangan', $this->keterangan, true);
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
	 * @return SkuTransfer the static model class
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
