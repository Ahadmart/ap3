<?php

/**
 * This is the model class for table "profil".
 *
 * The followings are the available columns in table 'profil':
 * @property string $id
 * @property integer $tipe_id
 * @property string $nama
 * @property string $alamat1
 * @property string $alamat2
 * @property string $alamat3
 * @property string $telp
 * @property string $keterangan
 * @property string $updated_at
 * @property string $updated_by
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property ProfilTipe $tipe
 * @property User $updatedBy
 */
class Profil extends CActiveRecord {

   const TIPE_SUPPLIER = 1;
   const TIPE_CUSTOMER = 2;
   const TIPE_KARYAWAN = 3;
   const AWAL_ID = 100; // id lebih kecil & / sama dari ini, untuk keperluan khusus. Untuk trx, mulai dari 101
   const PROFIL_INIT = 1; // Profil untuk init pembelian
   const PROFIL_UMUM = 2; // Default profil untuk penjualan

   public $profileTipeId;

   /**
    * @return string the associated database table name
    */
   public function tableName() {
      return 'profil';
   }

   /**
    * @return array validation rules for model attributes.
    */
   public function rules() {
      // NOTE: you should only define rules for those attributes that
      // will receive user inputs.
      return array(
          array('tipe_id, nama', 'required'),
          array('tipe_id', 'numerical', 'integerOnly' => true),
          array('nama, alamat1, alamat2, alamat3', 'length', 'max' => 100),
          array('telp', 'length', 'max' => 20),
          array('keterangan', 'length', 'max' => 1000),
          array('updated_by', 'length', 'max' => 10),
          array('created_at, updated_at, updated_by', 'safe'),
          // The following rule is used by search().
          // @todo Please remove those attributes that should not be searched.
          array('id, tipe_id, nama, alamat1, alamat2, alamat3, telp, keterangan, updated_at, updated_by, created_at', 'safe', 'on' => 'search'),
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      // NOTE: you may need to adjust the relation name and the related
      // class name for the relations automatically generated below.
      return array(
          'tipe' => array(self::BELONGS_TO, 'TipeProfil', 'tipe_id'),
          'updatedBy' => array(self::BELONGS_TO, 'User', 'updated_by'),
      );
   }

   /**
    * @return array customized attribute labels (name=>label)
    */
   public function attributeLabels() {
      return array(
          'id' => 'ID',
          'tipe_id' => 'Tipe',
          'nama' => 'Nama',
          'alamat1' => 'Alamat1',
          'alamat2' => 'Alamat2',
          'alamat3' => 'Alamat3',
          'telp' => 'Telp',
          'keterangan' => 'Keterangan',
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
      $criteria->compare('tipe_id', $this->tipe_id);
      $criteria->compare('nama', $this->nama, true);
      $criteria->compare('alamat1', $this->alamat1, true);
      $criteria->compare('alamat2', $this->alamat2, true);
      $criteria->compare('alamat3', $this->alamat3, true);
      $criteria->compare('telp', $this->telp, true);
      $criteria->compare('keterangan', $this->keterangan, true);
      $criteria->compare('updated_at', $this->updated_at, true);
      $criteria->compare('updated_by', $this->updated_by, true);
      $criteria->compare('created_at', $this->created_at, true);

      if (isset($this->profileTipeId)) {
         $criteria->addCondition('tipe_id='.$this->profileTipeId);
      }

      $sort = array(
          'defaultOrder' => 't.nama'
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
    * @return Profil the static model class
    */
   public static function model($className = __CLASS__) {
      return parent::model($className);
   }

   public function beforeSave() {

      if ($this->isNewRecord) {
         $this->created_at = date('Y-m-d H:i:s');
      }
      $this->updated_at = null; // Trigger current timestamp
      $this->updated_by = Yii::app()->user->id;
      return parent::beforeSave();
   }

   public function listSupplierYangBukan($barangId) {
      return Yii::app()->db->createCommand()
                      ->select('s.id, s.nama, s.alamat1, s.alamat2, s.alamat3')
                      ->from($this->tableName().' s')
                      ->where('s.tipe_id = 1 and s.id not in(select supplier_id from supplier_barang where barang_id = :barangId)', array(':barangId' => $barangId))
                      ->order('s.nama, s.alamat1')
                      ->queryAll();
   }

   public function getNamaTipe() {
      $tipeProfil = TipeProfil::model()->findByPk($this->tipe_id);
      return $tipeProfil->nama;
   }

}
