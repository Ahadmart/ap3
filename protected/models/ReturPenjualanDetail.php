<?php

/**
 * This is the model class for table "retur_penjualan_detail".
 *
 * The followings are the available columns in table 'retur_penjualan_detail':
 * @property string $id
 * @property string $retur_penjualan_id
 * @property string $penjualan_detail_id
 * @property string $qty
 * @property string $harga_jual
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property ReturPenjualan $returPenjualan
 * @property PenjualanDetail $penjualanDetail
 * @property User $updatedBy
 */
class ReturPenjualanDetail extends CActiveRecord {

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'retur_penjualan_detail';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('retur_penjualan_id, penjualan_detail_id, qty, harga_jual', 'required'),
          array('retur_penjualan_id, penjualan_detail_id, qty, updated_by', 'length', 'max' => 10),
          array('harga_jual', 'length', 'max' => 18),
          array('created_at, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, retur_penjualan_id, penjualan_detail_id, qty, harga_jual, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'returPenjualan' => array(self::BELONGS_TO, 'ReturPenjualan', 'retur_penjualan_id'),
          'penjualanDetail' => array(self::BELONGS_TO, 'PenjualanDetail', 'penjualan_detail_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'retur_penjualan_id' => 'Retur Penjualan',
          'penjualan_detail_id' => 'Penjualan Detail',
          'qty' => 'Qty',
          'harga_jual' => 'Harga Jual',
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

      $criteria->compare('id', $this->id);
      $criteria->compare('retur_penjualan_id', $this->retur_penjualan_id);
      $criteria->compare('penjualan_detail_id', $this->penjualan_detail_id);
      $criteria->compare('qty', $this->qty, true);
      $criteria->compare('harga_jual', $this->harga_jual, true);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      $sort = array(
          'defaultOrder' => 't.id desc'
      );

      return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
          'sort' => $sort
      ));
   }

   /**
    * Returns the static model of the specified AR class.
    * Please note that you should have this exact method in all your CActiveRecord descendants!
    * @param string $className active record class name.
    * @return ReturPenjualanDetail the static model class
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
   
   public function getTotal(){
      return $this->qty * $this->harga_jual;
   }

}
