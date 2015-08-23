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
      $date = isset($this->tanggal) ? date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d') : NULL;
      $laporanHarian = LaporanHarian::model()->find('tanggal=:tanggal', array(':tanggal' => $date));
      if (is_null($laporanHarian)) {
         /* Object, tidak untuk disimpan, hanya untuk mencari nilai per tanggal */
         $laporanHarian = new LaporanHarian;
         $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $this->tanggal), 'Y-m-d');
      } else {
         /* fixme: ganti afterFind() */
         $laporanHarian->tanggal = date_format(date_create_from_format('d-m-Y', $laporanHarian->tanggal), 'Y-m-d');
      }
      return array(
          'saldoAwal' => $laporanHarian->saldoAwal(),
          'saldoAkhir' => $laporanHarian->saldoAkhir(),
          'saldoAkhirAsli' => $laporanHarian->saldo_akhir,
          'keterangan' => $laporanHarian->keterangan,
          /* ========================================================== */
          'penjualanTunai' => $laporanHarian->penjualanTunai(),
          'totalPenjualanTunai' => $laporanHarian->totalPenjualanTunai(),
          'penjualanPiutang' => $laporanHarian->penjualanPiutang(),
          'totalPenjualanPiutang' => $laporanHarian->totalPenjualanPiutang(),
          'penjualanBayar' => $laporanHarian->penjualanBayar(),
          'totalPenjualanBayar' => $laporanHarian->totalPenjualanBayar(),
          /* ========================================================== */
          'margin' => $laporanHarian->marginPenjualanTunai(),
          'totalMargin' => $laporanHarian->totalMarginPenjualanTunai(),
          /* ========================================================== */
          'pembelianTunai' => $laporanHarian->pembelianTunai(),
          'totalPembelianTunai' => $laporanHarian->totalPembelianTunai(),
          'pembelianHutang' => $laporanHarian->pembelianHutang(),
          'totalPembelianHutang' => $laporanHarian->totalPembelianHutang(),
          'pembelianBayar' => $laporanHarian->pembelianBayar(),
          'totalPembelianBayar' => $laporanHarian->totalPembelianBayar(),
          /* ========================================================== */
          'itemPengeluaran' => $laporanHarian->itemPengeluaran(),
          'itemPenerimaan' => $laporanHarian->itemPenerimaan(),
          /* ========================================================== */
          'returBeliTunai' => $laporanHarian->returBeliTunai(),
          'totalReturBeliTunai' => $laporanHarian->totalReturBeliTunai(),
          'returBeliPiutang' => $laporanHarian->returBeliPiutang(),
          'totalReturBeliPiutang' => $laporanHarian->totalReturBeliPiutang(),
          'returBeliBayar' => $laporanHarian->returBeliBayar(),
          'totalReturBeliBayar' => $laporanHarian->totalReturBeliBayar(),
          /* ========================================================== */
          'returJualTunai' => $laporanHarian->returJualTunai(),
          'totalReturJualTunai' => $laporanHarian->totalReturJualTunai(),
          'returJualHutang' => $laporanHarian->returJualHutang(),
          'totalReturJualHutang' => $laporanHarian->totalReturJualHutang(),
          'returJualBayar' => $laporanHarian->returJualBayar(),
          'totalReturJualBayar' => $laporanHarian->totalReturJualBayar(),
      );
   }

}
