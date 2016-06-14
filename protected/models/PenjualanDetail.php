<?php

/**
 * This is the model class for table "penjualan_detail".
 *
 * The followings are the available columns in table 'penjualan_detail':
 * @property string $id
 * @property string $penjualan_id
 * @property string $barang_id
 * @property string $qty
 * @property string $harga_jual
 * @property string $harga_jual_rekomendasi
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Penjualan $penjualan
 * @property User $updatedBy
 * @property ReturPenjualanDetail[] $returPenjualanDetails
 */
class PenjualanDetail extends CActiveRecord {

   public $nomorPenjualan;
   public $statusPenjualan;
   public $barcode;
   public $namaBarang;

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'penjualan_detail';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('penjualan_id, barang_id, harga_jual', 'required'),
          array('penjualan_id, barang_id, qty, updated_by', 'length', 'max' => 10),
          array('harga_jual, harga_jual_rekomendasi', 'length', 'max' => 18),
          array('created_at, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, penjualan_id, barang_id, qty, harga_jual, harga_jual_rekomendasi, updated_at, updated_by, created_at, namaBarang, barcode, nomorPenjualan, nomorPenjualan', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'barang' => array(self::BELONGS_TO, 'Barang', 'barang_id'),
          'penjualan' => array(self::BELONGS_TO, 'Penjualan', 'penjualan_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
          'returPenjualanDetails' => array(self::HAS_MANY, 'ReturPenjualanDetail', 'penjualan_detail_id'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'penjualan_id' => 'Penjualan',
          'barang_id' => 'Barang',
          'qty' => 'Qty',
          'harga_jual' => 'Harga Jual',
          'harga_jual_rekomendasi' => 'RRP',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'created_at' => 'Created At',
          'namaBarang' => 'Nama',
          'nomorPenjualan' => 'Penjualan'
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

      $criteria->compare('t.id', $this->id, true);
      $criteria->compare('penjualan_id', $this->penjualan_id, true);
      $criteria->compare('barang_id', $this->barang_id, true);
      $criteria->compare('qty', $this->qty, true);
      $criteria->compare('harga_jual', $this->harga_jual, true);
      $criteria->compare('harga_jual_rekomendasi', $this->harga_jual_rekomendasi, true);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      $criteria->with = array('barang', 'penjualan');
      $criteria->compare('barang.nama', $this->namaBarang, true);
      $criteria->compare('barang.barcode', $this->barcode, true);
      $criteria->compare('penjualan.nomor', $this->nomorPenjualan, true);
      $criteria->compare('penjualan.status', $this->statusPenjualan);

      $sort = array(
          'defaultOrder' => 't.id desc',
          'attributes' => array(
              '*',
              'namaBarang' => array(
                  'asc' => 'barang.nama',
                  'desc' => 'barang.nama desc'
              ),
              'barcode' => array(
                  'asc' => 'barang.barcode',
                  'desc' => 'barang.barcode desc'
              ),
              'nomorPenjualan' => array(
                  'asc' => 'penjualan.nomor',
                  'desc' => 'penjualan.nomor desc'
              ),
          )
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
    * @return PenjualanDetail the static model class
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

   public function getTotal() {
      return number_format($this->harga_jual * $this->qty, 0, ',', '.');
   }

}
