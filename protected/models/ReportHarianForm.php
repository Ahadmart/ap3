<?php

/**
 * ReportHarianForm class.
 * ReportHarianForm is the data structure for keeping
 * report harian form data. It is used by the 'harian' action of 'ReportController'.
 * 
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportHarianForm extends CFormModel {

   public $tanggal;

   /**
    * Declares the validation rules.
    */
   public function rules() {
      return array(
          array('tanggal', 'required', 'message' => '{attribute} tidak boleh kosong'),
      );
   }

   /**
    * Declares attribute labels.
    */
   public function attributeLabels() {
      return array(
          'tanggal' => 'Tanggal'
      );
   }

   public function reportHarian() {
      $tanggal = date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d');

      $command = Yii::app()->db->createCommand();
      $command->select('sum(harga_jual) total');
      $command->from(PenjualanDetail::model()->tableName().' detail');
      $command->join(Penjualan::model()->tableName().' pj', 'detail.penjualan_id=pj.id');
      $command->where("date_format(pj.tanggal,'%Y-%m-%d') = :tanggal", array(
          ':tanggal' => $tanggal,));

      $omzet = $command->queryRow();
      return array('omzet' => $omzet['total']);
   }

}
