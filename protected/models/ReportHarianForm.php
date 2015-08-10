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

   /**
    * Report Harian
    * @return array Nilai-nilai yang diperlukan untuk report harian
    */
   public function reportHarian() {
      $tanggal = date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d');

      return array(
          'omzet' => $this->_omzet($tanggal),
          'pembelianTunai' => $this->_pembelianTunai($tanggal)
      );
   }

   /**
    * Total omzet per tanggal
    * @param date $tanggal
    * @return decimal total omzet
    */
   private function _omzet($tanggal) {
      $command = Yii::app()->db->createCommand();
      $command->select('sum(harga_jual) total');
      $command->from(PenjualanDetail::model()->tableName().' detail');
      $command->join(Penjualan::model()->tableName().' pj', 'detail.penjualan_id=pj.id');
      $command->where("date_format(pj.tanggal,'%Y-%m-%d') = :tanggal", array(
          ':tanggal' => $tanggal,));

      $omzet = $command->queryRow();
      return $omzet['total'];
   }

   /**
    * Pembelian yang dibayar di hari yang sama
    * @param date $tanggal
    * @return array Pembelian tunai per trx (nomor pembelian, profil, total)
    */
   private function _pembelianTunai($tanggal) {
      $command = Yii::app()->db->createCommand();
      $command->select('distinct profil.nama,p.nomor, 
        p.tanggal, hp.nomor, hp.jumlah,
        sum(kd.jumlah) bayar, kd.updated_at, sum(pd.jumlah) terima, pd.updated_at');
      $command->from(Pembelian::model()->tableName().' p');
      $command->join(HutangPiutang::model()->tableName().' hp', 'p.hutang_piutang_id = hp.id');
      $command->join(Profil::model()->tableName(), 'p.profil_id = profil.id');
      $command->leftJoin(PengeluaranDetail::model()->tableName().' kd', 'hp.id=kd.hutang_piutang_id');
      $command->leftJoin(Pengeluaran::model()->tableName(), "kd.pengeluaran_id = pengeluaran.id and pengeluaran.status=1 and date_format(pengeluaran.tanggal,'%Y-%m-%d')= :tanggal");
      $command->leftJoin(PenerimaanDetail::model()->tableName().' pd', 'hp.id=pd.hutang_piutang_id');
      $command->leftJoin(Penerimaan::model()->tableName(), "pd.penerimaan_id = penerimaan.id and penerimaan.status=1 and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal");
      $command->where("date_format(p.tanggal,'%Y-%m-%d') = :tanggal");
      $command->group('p.nomor, p.tanggal, hp.nomor');
      $command->having('sum(ifnull(kd.jumlah,0)) + sum(ifnull(pd.jumlah,0)) > 0');
      $command->bindValue(':tanggal', $tanggal);

      return $command->queryAll();
   }

}
