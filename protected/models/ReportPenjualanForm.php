<?php

/**
 * ReportPenjualanForm class.
 * ReportPenjualanForm is the data structure for keeping
 * report penjualan form data. It is used by the 'penjualan' action of 'ReportController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportPenjualanForm extends CFormModel {

   public $profilId;
   public $userId;
   public $dari;
   public $sampai;

   /**
    * Declares the validation rules.
    */
   public function rules() {
      return array(
          array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
          array('profilId, userId', 'safe')
      );
   }

   /**
    * Declares attribute labels.
    */
   public function attributeLabels() {
      return array(
          'profilId' => 'Profil',
          'userId' => 'User',
          'dari' => 'Dari',
          'sampai' => 'Sampai'
      );
   }

   /**
    * @return array relational rules.
    */
   public function relations() {
      return array(
          'profil' => array(self::BELONGS_TO, 'Profil', 'profilId'),
          'user' => array(self::BELONGS_TO, 'User', 'userId'),
      );
   }

   public function getNamaProfil() {
      $profil = Profil::model()->findByPk($this->profilId);
      return $profil->nama;
   }

   public function getNamaUser() {
      $user = User::model()->findByPk($this->userId);
      return $user->nama;
   }

   public function reportPenjualan() {
      $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
      $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');
      // echo $dari.' sampai '.$sampai;
      $criteria = new CDbCriteria();
      $criteria->addBetweenCondition("date_format(tanggal,'%Y-%m-%d')", $dari, $sampai);
//      if (!empty($this->profilId)) {
//         $criteria->addCondition('profil_id=:profilId', array(':profilId' => $this->profilId));
//      }
      $penjualan = Penjualan::model()->findAll($criteria);
      return $penjualan;
   }

}
