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

}
