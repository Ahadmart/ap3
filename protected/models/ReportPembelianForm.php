<?php

/**
 * ReportPembelianForm class.
 * ReportPembelianForm is the data structure for keeping
 * report pembelian form data. It is used by the 'pembelian' action of 'ReportController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportPembelianForm extends CFormModel {

   public $profilId;
   public $dari;
   public $sampai;

   /**
    * Declares the validation rules.
    */
   public function rules() {
      return array(
          array('dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'),
          array('profilId', 'safe')
      );
   }

   /**
    * Declares attribute labels.
    */
   public function attributeLabels() {
      return array(
          'profilId' => 'Profil',
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
      );
   }

   public function getNamaProfil() {
      $profil = Profil::model()->findByPk($this->profilId);
      return $profil->nama;
   }

   public function reportPembelian() {
      $dari = date_format(date_create_from_format('d-m-Y', $this->dari), 'Y-m-d');
      $sampai = date_format(date_create_from_format('d-m-Y', $this->sampai), 'Y-m-d');
      // echo $dari.' sampai '.$sampai;
      $criteria = new CDbCriteria();
      $criteria->addBetweenCondition("date_format(tanggal,'%Y-%m-%d')", $dari, $sampai);
//      if (!empty($this->profilId)) {
//         $criteria->addCondition('profil_id=:profilId', array(':profilId' => $this->profilId));
//      }
      $pembelian = Pembelian::model()->findAll($criteria);
      return $pembelian;
   }

}
