<?php

/**
 * ReportPembelianForm class.
 * ReportPembelianForm is the data structure for keeping
 * report pembelian form data. It is used by the 'pembelian' action of 'ReportController'.
 *
 * The followings are the available model relations:
 * @property Profil $profil
 */
class ReportPembelianForm extends CFormModel
{
   public $profilId;
   public $dari;
   public $sampai;
   public $printer;

   /**
    * Declares the validation rules.
    */
   public function rules()
   {
      return [
         ['dari, sampai', 'required', 'message' => '{attribute} tidak boleh kosong'],
         ['profilId, printer', 'safe'],
      ];
   }

   /**
    * Declares attribute labels.
    */
   public function attributeLabels()
   {
      return [
         'profilId' => 'Profil',
         'dari'     => 'Dari',
         'sampai'   => 'Sampai',
      ];
   }

   /**
    * @return array relational rules.
    */
   public function relations()
   {
      return [
         'profil' => [self::BELONGS_TO, 'Profil', 'profilId'],
      ];
   }

   public function getNamaProfil()
   {
      $profil = Profil::model()->findByPk($this->profilId);
      return $profil->nama;
   }

   public function reportPembelian()
   {
      $dari   = DateTime::createFromFormat('d-m-Y', $this->dari);
      $sampai = DateTime::createFromFormat('d-m-Y', $this->sampai);
      $sampai->modify('+1 day');

      $tanggalAwal  = $dari->format('Y-m-d') . ' 00:00:00';
      $tanggalAkhir = $sampai->format('Y-m-d') . ' 00:00:00';

      $profilCond = '';
      if (!empty($this->profilId)) {
         $profilCond .= " AND p.profil_id = :profilId";
      }

      $sql = "
         SELECT
            pembelian_id,
            p.tanggal,
            p.referensi,
            p.nomor,
            ROUND(SUM(harga_beli * qty)) AS jumlah,
            profil.nama AS profil
         FROM
            pembelian_detail AS d
               JOIN
            pembelian AS p ON p.id = d.pembelian_id
               AND p.tanggal >= :tanggalAwal
               AND p.tanggal < :tanggalAkhir
               AND p.status != :statusDraft
               JOIN
            profil ON profil.id = p.profil_id {$profilCond}
         GROUP BY p.id
        ";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindValue(":tanggalAwal", $tanggalAwal);
      $command->bindValue(":tanggalAkhir", $tanggalAkhir);
      $command->bindValue(":statusDraft", Pembelian::STATUS_DRAFT);

      if (!empty($this->profilId)) {
         $command->bindValue(":profilId", $this->profilId);
      }

      $r['detail'] = $command->queryAll();

      return $r;
   }

   public function toCsv()
   {
      $result = $this->reportPembelian();
      return $this->array2csv($result['detail']);
   }

   public function array2csv(array &$array)
   {
      if (count($array) == 0) {
         return null;
      }
      ob_start();
      $df = fopen('php://output', 'w');
      fputcsv($df, array_keys(reset($array)));
      foreach ($array as $row) {
         fputcsv($df, $row);
      }
      fclose($df);
      return ob_get_clean();
   }
}
