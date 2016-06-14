<?php

/**
 * This is the model class for table "harga_pokok_penjualan".
 *
 * The followings are the available columns in table 'harga_pokok_penjualan':
 * @property string $id
 * @property string $pembelian_detail_id
 * @property string $penjualan_detail_id
 * @property string $qty
 * @property string $harga_beli
 * @property string $harga_beli_temp
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property User $updatedBy
 */
class HargaPokokPenjualan extends CActiveRecord {

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'harga_pokok_penjualan';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('pembelian_detail_id, penjualan_detail_id, qty', 'required'),
          array('pembelian_detail_id, penjualan_detail_id, qty, updated_by', 'length', 'max' => 10),
          array('harga_beli, harga_beli_temp', 'length', 'max' => 18),
          array('created_at, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, pembelian_detail_id, penjualan_detail_id, qty, harga_beli, harga_beli_temp, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'pembelian_detail_id' => 'Pembelian Detail',
          'penjualan_detail_id' => 'Penjualan Detail',
          'qty' => 'Qty',
          'harga_beli' => 'Harga Beli',
          'harga_beli_temp' => 'Harga Beli Temp',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'created_at' => 'Created At',
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
   public function search() {
      // @todo Please modify the following code to remove attributes that should not be searched.

      $criteria = new CDbCriteria;

      $criteria->compare('id', $this->id, true);
      $criteria->compare('pembelian_detail_id', $this->pembelian_detail_id, true);
      $criteria->compare('penjualan_detail_id', $this->penjualan_detail_id, true);
      $criteria->compare('qty', $this->qty, true);
      $criteria->compare('harga_beli', $this->harga_beli, true);
      $criteria->compare('harga_beli_temp', $this->harga_beli_temp, true);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
      ));
   }

   /**
    * Returns the static model of the specified AR class.
    * Please note that you should have this exact method in all your CActiveRecord descendants!
    * @param string $className active record class name.
    * @return HargaPokokPenjualan the static model class
    */
   public static function model($className = __CLASS__) {
      return parent::model($className);
   }

   public function beforeSave() {

      if ($this->isNewRecord) {
         $this->created_at = date('Y-m-d H:i:s');
      }
      $this->updated_at = date("Y-m-d H:i:s");
      $this->updated_by = Yii::app()->user->id;
      return parent::beforeSave();
   }

}
