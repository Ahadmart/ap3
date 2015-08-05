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
      if (isset($_POST['ReportPenjualanForm'])) {
         $model->attributes = $_POST['ReportPenjualanForm'];
         if ($model->validate()) {
            $report = $model->reportPenjualan();
            echo '<pre>';
            print_r($report);
            echo '</pre>';
            Yii::app()->end();
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
          'user' => $user
      ));
   }

   /**
    * Untuk menampilkan link nama_lengkap, untuk pilih user
    * @param obj $data
    * @return string link pilih user
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

}
