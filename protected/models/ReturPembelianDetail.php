<?php

/**
 * This is the model class for table "retur_pembelian_detail".
 *
 * The followings are the available columns in table 'retur_pembelian_detail':
 * @property string $id
 * @property string $retur_pembelian_id
 * @property string $inventory_balance_id
 * @property string $qty
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property ReturPembelian $returPembelian
 * @property InventoryBalance $inventoryBalance
 * @property User $updatedBy
 */
class ReturPembelianDetail extends CActiveRecord {

   public $barcode;
   public $namaBarang;
   public $faktur;
   public $tglFaktur;
   public $pembelian;
   public $tglPembelian;
   public $hargaBeli;

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'retur_pembelian_detail';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('retur_pembelian_id, inventory_balance_id', 'required'),
          array('retur_pembelian_id, inventory_balance_id, qty, updated_by', 'length', 'max' => 10),
          array('created_at, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, retur_pembelian_id, inventory_balance_id, qty, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'returPembelian' => array(self::BELONGS_TO, 'ReturPembelian', 'retur_pembelian_id'),
          'inventoryBalance' => array(self::BELONGS_TO, 'InventoryBalance', 'inventory_balance_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'retur_pembelian_id' => 'Retur Pembelian',
          'inventory_balance_id' => 'Inventory Balance',
          'qty' => 'Qty',
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
      $criteria->compare('retur_pembelian_id', $this->retur_pembelian_id, true);
      $criteria->compare('inventory_balance_id', $this->inventory_balance_id, true);
      $criteria->compare('qty', $this->qty, true);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      return new CActiveDataProvider($this, array(
          'criteria' => $criteria,
          'sort' => array(
              'defaultOrder' => 'id desc'
          )
      ));
   }

   /**
    * Returns the static model of the specified AR class.
    * Please note that you should have this exact method in all your CActiveRecord descendants!
    * @param string $className active record class name.
    * @return ReturPembelianDetail the static model class
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

   public function getSubTotal() {
      return number_format($this->qty * $this->inventoryBalance->harga_beli, 0, ',', '.');
   }

}
