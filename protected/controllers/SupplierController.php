<?php

class SupplierController extends Controller {

   /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
   public function actionView($id) {
      $this->layout = '//layouts/box_kecil';

      $this->render('view', array(
          'model' => $this->loadModel($id),
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new Profil;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Profil'])) {
         $model->attributes = $_POST['Profil'];
         $model->tipe_id = Profil::TIPE_SUPPLIER;
         if ($model->save())
            $this->redirect(array('view', 'id' => $model->id));
      }

      $this->render('tambah', array(
          'model' => $model,
      ));
   }

   /**
    * Updates a particular model.
    * @param integer $id the ID of the model to be updated
    */
   public function actionUbah($id) {
      $this->layout = '//layouts/box_kecil';
      $model = $this->loadModel($id);

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Profil'])) {
         $model->attributes = $_POST['Profil'];
         if ($model->save())
            $this->redirect(array('view', 'id' => $id));
      }

      $this->render('ubah', array(
          'model' => $model,
      ));
   }

   /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'admin' page.
    * @param integer $id the ID of the model to be deleted
    */
   public function actionHapus($id) {
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
         $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
   }

   /**
    * Manages all models.
    */
   public function actionIndex() {
      $model = new Profil('search');
      $model->unsetAttributes();  // clear any default values
      $model->profileTipeId = Profil::TIPE_SUPPLIER;

      if (isset($_GET['Profil']))
         $model->attributes = $_GET['Profil'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Profil the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = Profil::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param Profil $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'profil-form') {
         echo CActiveForm::validate($model);
         Yii::app()->end();
      }
   }

}
