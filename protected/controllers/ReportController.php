<?php

class ReportController extends Controller {

   public function actionIndex() {
      $this->render('index');
   }

   public function actionPilihProfil($id) {
      $profil = Profil::model()->findByPk($id);
      $return = array(
          'id' => $id,
          'nama' => $profil->nama,
          'alamat1' => $profil->alamat1,
      );
      $this->renderJSON($return);
   }

   /**
    * Report Pembelian Form
    */
   public function actionPembelian() {
      $model = new ReportPembelianForm;
      if (isset($_POST['ReportPembelianForm'])) {
         $model->attributes = $_POST['ReportPembelianForm'];
         if ($model->validate()) {
            $report = $model->reportPembelian();
         }
      }

      $profil = new Profil('search');
      $profil->unsetAttributes();  // clear any default values
      if (isset($_GET['Profil'])) {
         $profil->attributes = $_GET['Profil'];
      }

      $this->render('pembelian', array(
          'model' => $model,
          'profil' => $profil
      ));
   }

   /**
    * Report Penjualan Form
    */
   public function actionPenjualan() {
      $model = new ReportPenjualanForm;
      $report = array();
      if (isset($_POST['ReportPenjualanForm'])) {
         $model->attributes = $_POST['ReportPenjualanForm'];
         if ($model->validate()) {
            $report = $model->reportPenjualan();
         }
      }

      $profil = new Profil('search');
      $profil->unsetAttributes();  // clear any default values
      if (isset($_GET['Profil'])) {
         $profil->attributes = $_GET['Profil'];
      }

      $user = new User('search');
      $user->unsetAttributes();  // clear any default values
      if (isset($_GET['User'])) {
         $user->attributes = $_GET['User'];
      }

      $this->render('penjualan', array(
          'model' => $model,
          'profil' => $profil,
          'user' => $user,
          'report' => $report,
      ));
   }

   /**
    * Untuk menampilkan link nama_lengkap, untuk pilih user
    * @param obj $data
    * @return text link pilih user
    */
   public function renderLinkPilihUser($data) {
      $return = '';
      if (isset($data->nama)) {
         $return = '<a href="'.
                 $this->createUrl('pilihuser', array('id' => $data->id)).
                 '" class="pilih user">'.$data->nama_lengkap.'</a>';
      }
      return $return;
   }

   public function actionPilihUser($id) {
      $user = User::model()->findByPk($id);
      $return = array(
          'id' => $id,
          'namaLengkap' => $user->nama_lengkap,
          'nama' => $user->nama,
      );
      $this->renderJSON($return);
   }

   /**
    * Report Harian Form
    */
   public function actionHarian() {
      $this->layout = '//layouts/box_kecil';
      $model = new ReportHarianForm;
      if (isset($_REQUEST['ReportHarianForm'])) {
         $model->attributes = $_REQUEST['ReportHarianForm'];
         if ($model->validate()) {
            $report = $model->reportHarian();
            $report['tanggal'] = $model->tanggal;
            $report['namaToko'] = $this->namaToko();
            $report['kodeToko'] = $this->kodeToko();
            $this->harianPdf($report);
            Yii::app()->end();
         }
      }

      $this->render('harian', array(
          'model' => $model,
      ));
   }

   public function namaToko() {
      $config = Config::model()->find('nama=:nama', array(':nama' => 'nama'));
      return $config->nilai;
   }
   
   public function kodeToko() {
      $config = Config::model()->find('nama=:nama', array(':nama' => 'kode'));
      return $config->nilai;
   }
   
   public function harianPdf($report) {

      /*
       * Persiapan render PDF
       */
      $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
      $mPDF1->WriteHTML($this->renderPartial('harian_pdf', array(
                  'report' => $report,
                      ), true
      ));

      $mPDF1->SetDisplayMode('fullpage');
      $mPDF1->pagenumPrefix = 'Hal ';
      $mPDF1->pagenumSuffix = ' / ';
      // Render PDF
      $mPDF1->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
   }

}
