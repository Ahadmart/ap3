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
          'penjualanTunai' => $this->_penjualanTunai($tanggal),
          'totalPenjualanTunai' => $this->_totalPenjualanTunai($tanggal),
          'pembelianTunai' => $this->_pembelianTunai($tanggal),
          'totalPembelianTunai' => $this->_totalPembelianTunai($tanggal),
          'pembelianHutang' => $this->_pembelianHutang($tanggal),
          'totalPembelianHutang' => $this->_totalPembelianHutang($tanggal),
          'pembelianBayar' => $this->_pembelianBayar($tanggal),
          'totalPembelianBayar' => $this->_totalPembelianBayar($tanggal)
      );
   }

   /**
    * Pembelian yang dibayar di hari yang sama
    * @param date $tanggal
    * @return array Pembelian tunai per trx (nomor pembelian, profil, total)
    */
   private function _pembelianTunai($tanggal) {
      $command = Yii::app()->db->createCommand();
      $command->select('distinct profil.nama,p.nomor, 
        p.tanggal, hp.nomor nomor_hp, hp.jumlah,
        sum(kd.jumlah) bayar, kd.updated_at, sum(pd.jumlah) terima, pd.updated_at');
      $command->from(Pembelian::model()->tableName().' p');
      $command->join(HutangPiutang::model()->tableName().' hp', 'p.hutang_piutang_id = hp.id');
      $command->join(Profil::model()->tableName(), 'p.profil_id = profil.id');
      $command->leftJoin(PengeluaranDetail::model()->tableName().' kd', 'hp.id=kd.hutang_piutang_id');
      $command->leftJoin(Pengeluaran::model()->tableName(), "kd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')= :tanggal");
      $command->leftJoin(PenerimaanDetail::model()->tableName().' pd', 'hp.id=pd.hutang_piutang_id');
      $command->leftJoin(Penerimaan::model()->tableName(), "pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal");
      $command->where("date_format(p.tanggal,'%Y-%m-%d') = :tanggal");
      $command->group('p.nomor, p.tanggal, hp.nomor');
      $command->having('sum(ifnull(kd.jumlah,0)) + sum(ifnull(pd.jumlah,0)) > 0');

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      return $command->queryAll();
   }

   private function _totalPembelianTunai($tanggal) {
      $command = Yii::app()->db->createCommand();
      $command->select('sum(ifnull(kd.jumlah,0) + ifnull(pd.jumlah,0)) total');
      $command->from(Pembelian::model()->tableName().' p');
      $command->join(HutangPiutang::model()->tableName().' hp', 'p.hutang_piutang_id = hp.id');
      $command->leftJoin(PengeluaranDetail::model()->tableName().' kd', 'hp.id=kd.hutang_piutang_id');
      $command->leftJoin(Pengeluaran::model()->tableName(), "kd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')= :tanggal");
      $command->leftJoin(PenerimaanDetail::model()->tableName().' pd', 'hp.id=pd.hutang_piutang_id');
      $command->leftJoin(Penerimaan::model()->tableName(), "pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal");
      $command->where("date_format(p.tanggal,'%Y-%m-%d') = :tanggal");
      $command->having('sum(ifnull(kd.jumlah,0)) + sum(ifnull(pd.jumlah,0)) > 0');

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      $pembelian = $command->queryRow();
      return $pembelian['total'];
   }

   /**
    * Pembelian yang masih hutang
    * @param date $tanggal
    * @return array Pembelian pada tanggal tsb yang masih hutang per trx (nomor pembelian, profil, total)
    */
   private function _pembelianHutang($tanggal) {
      $command = Yii::app()->db->createCommand();
      $command->select('distinct profil.nama,p.nomor, 
        p.tanggal, hp.nomor hp_nomor, hp.jumlah,
        sum(kd.jumlah) bayar, kd.updated_at, sum(pd.jumlah) terima, pd.updated_at');
      $command->from(Pembelian::model()->tableName().' p');
      $command->join(HutangPiutang::model()->tableName().' hp', 'p.hutang_piutang_id = hp.id');
      $command->join(Profil::model()->tableName(), 'p.profil_id = profil.id');
      $command->leftJoin(PengeluaranDetail::model()->tableName().' kd', 'hp.id=kd.hutang_piutang_id');
      $command->leftJoin(Pengeluaran::model()->tableName(), "kd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')= :tanggal");
      $command->leftJoin(PenerimaanDetail::model()->tableName().' pd', 'hp.id=pd.hutang_piutang_id');
      $command->leftJoin(Penerimaan::model()->tableName(), "pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal");
      $command->where("date_format(p.tanggal,'%Y-%m-%d') = :tanggal");
      $command->group('p.nomor, p.tanggal, hp.nomor');
      $command->having('sum(ifnull(kd.jumlah,0)) + sum(ifnull(pd.jumlah,0)) < hp.jumlah');

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      return $command->queryAll();
   }

   private function _totalPembelianHutang($tanggal) {
      $command = Yii::app()->db->createCommand("
            select sum(total_hutang) total
            from(
            select hp.jumlah, hp.jumlah-(sum(ifnull(kd.jumlah,0))+sum(ifnull(pd.jumlah,0))) total_hutang
            from pembelian p
            join hutang_piutang hp on p.hutang_piutang_id=hp.id
            join profil on p.profil_id = profil.id
            left join pengeluaran_detail kd on hp.id=kd.hutang_piutang_id
            left join pengeluaran on kd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')=:tanggal
            left join penerimaan_detail pd on hp.id=pd.hutang_piutang_id
            left join penerimaan on pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal
            where  date_format(p.tanggal,'%Y-%m-%d')=:tanggal
            group by p.nomor, p.tanggal, hp.nomor
            having sum(ifnull(kd.jumlah,0)) + sum(ifnull(pd.jumlah,0)) < hp.jumlah
            ) t");

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      $hutangPembelian = $command->queryRow();
      return $hutangPembelian['total'];
   }

   /**
    * Pembelian yang dibayar pada tanggal $tanggal, per nomor pembelian
    * @param date $tanggal
    * @return array nomor pembelian, nama profil, tanggal pembelian, total pembayaran
    */
   private function _pembelianBayar($tanggal) {
      $command = Yii::app()->db->createCommand("
         select pembelian.nomor, profil.nama, pembelian.tanggal, t2.total_bayar
         from
         (
            select id, sum(jumlah_bayar) total_bayar
            from
            (
               select sum(pd.jumlah) jumlah_bayar, pembelian.id
               from pengeluaran_detail pd
               join pengeluaran on pd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')=:tanggal
               join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
               join pembelian on hp.id=pembelian.hutang_piutang_id and date_format(pembelian.tanggal,'%Y-%m-%d')<:tanggal
               group by pembelian.id
               union
               select sum(pd.jumlah) jumlah_bayar, pembelian.id
               from penerimaan_detail pd
               join penerimaan on pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal
               join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
               join pembelian on hp.id=pembelian.hutang_piutang_id and date_format(pembelian.tanggal,'%Y-%m-%d')<:tanggal
               group by pembelian.id
            ) t1
            group by id
         ) t2
         join pembelian on t2.id=pembelian.id
         join profil on pembelian.profil_id = profil.id");

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      return $command->queryAll();
   }

   private function _totalPembelianBayar($tanggal) {
      $command = Yii::app()->db->createCommand("
         select sum(jumlah_bayar) total
         from
         (
            select sum(pd.jumlah) jumlah_bayar, pembelian.id
            from pengeluaran_detail pd
            join pengeluaran on pd.pengeluaran_id = pengeluaran.id and pengeluaran.status=:statusPengeluaran and date_format(pengeluaran.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=:asalHutangPiutang
            join pembelian on hp.id=pembelian.hutang_piutang_id and date_format(pembelian.tanggal,'%Y-%m-%d')<:tanggal
            group by pembelian.id
            union
            select sum(pd.jumlah) jumlah_bayar, pembelian.id
            from penerimaan_detail pd
            join penerimaan on pd.penerimaan_id = penerimaan.id and penerimaan.status=:statusPenerimaan and date_format(penerimaan.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on pd.hutang_piutang_id=hp.id and hp.asal=1
            join pembelian on hp.id=pembelian.hutang_piutang_id and date_format(pembelian.tanggal,'%Y-%m-%d')<:tanggal
            group by pembelian.id
         ) t1");

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':asalHutangPiutang' => HutangPiutang::DARI_PEMBELIAN,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      $bayarPembelian = $command->queryRow();
      return $bayarPembelian['total'];
   }

   /**
    * Penjualan tunai yang terjadi pada tanggal $tanggal
    * @param date $tanggal Tanggal transaksi
    * @return array nomor, nama, jumlah dari penjualan tunai
    */
   private function _penjualanTunai($tanggal) {
      $command = Yii::app()->db->createCommand("
         select nomor, sum(jumlah) jumlah, profil.nama
         FROM
         (
            select penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and date_format(p.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and date_format(penjualan.tanggal,'%Y-%m-%d')=:tanggal
            union
            select penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and date_format(p.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and date_format(penjualan.tanggal,'%Y-%m-%d')=:tanggal
         ) t 
         join penjualan on t.id = penjualan.id
         join profil on penjualan.profil_id = profil.id
         group by t.id");

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      return $command->queryAll();
   }

   /**
    * Total Penjualan Tunai pada tanggal $tanggal
    * @param date $tanggal Tanggal Trx
    * @return text Total penjualan tunai
    */
   private function _totalPenjualanTunai($tanggal) {
      $command = Yii::app()->db->createCommand("
         select sum(jumlah) total
         FROM
         (
            select penjualan.id, d.jumlah
            from penerimaan_detail d
            join penerimaan p on d.penerimaan_id = p.id and p.status=:statusPenerimaan and date_format(p.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and date_format(penjualan.tanggal,'%Y-%m-%d')=:tanggal
            union
            select penjualan.id, d.jumlah
            from pengeluaran_detail d
            join pengeluaran p on d.pengeluaran_id = p.id and p.status=:statusPengeluaran and date_format(p.tanggal,'%Y-%m-%d')=:tanggal
            join hutang_piutang hp on d.hutang_piutang_id = hp.id and hp.asal=:asalHutangPiutang
            join penjualan on hp.id = penjualan.hutang_piutang_id and date_format(penjualan.tanggal,'%Y-%m-%d')=:tanggal
         ) t
         ");

      $command->bindValues(array(
          ':tanggal' => $tanggal,
          ':asalHutangPiutang' => HutangPiutang::DARI_PENJUALAN,
          ':statusPengeluaran' => Pengeluaran::STATUS_BAYAR,
          ':statusPenerimaan' => Penerimaan::STATUS_BAYAR
      ));

      $penjualanTunai = $command->queryRow();
      return $penjualanTunai['total'];
   }

}
