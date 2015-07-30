<?php

class ReturpenjualanController extends Controller {

   /**
    * @return array action filters
    */
   public function filters() {
      return array(
          'accessControl', // perform access control for CRUD operations
          'postOnly + delete', // we only allow deletion via POST request
      );
   }

   /**
    * Specifies the access control rules.
    * This method is used by the 'accessControl' filter.
    * @return array access control rules
    */
   public function accessRules() {
      return array(
          array('deny', // deny guest
              'users' => array('guest'),
          ),
      );
   }

   /**
    * Displays a particular model.
    * @param integer $id the ID of the model to be displayed
    */
   public function actionView($id) {
      $returPenjualanDetail = new ReturPenjualanDetail('search');
      $returPenjualanDetail->unsetAttributes();
      $returPenjualanDetail->setAttribute('retur_penjualan_id', '='.$id);

      $this->render('view', array(
          'model' => $this->loadModel($id),
          'returPenjualanDetail' => $returPenjualanDetail
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'ubah' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new ReturPenjualan;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['ReturPenjualan'])) {
         $model->attributes = $_POST['ReturPenjualan'];
         if ($model->save())
            $this->redirect(array('ubah', 'id' => $model->id));
      }

      $customerList = Profil::model()->findAll(array(
          'select' => 'id, nama',
          'condition' => 'id>'.Profil::AWAL_ID.' and tipe_id='.Profil::TIPE_CUSTOMER,
          'order' => 'nama'
      ));

      $this->render('tambah', array(
          'model' => $model,
          'customerList' => $customerList,
      ));
   }

   /**
    * Updates a particular model.
    * @param integer $id the ID of the model to be updated
    */
   public function actionUbah($id) {
      $model = $this->loadModel($id);

      if ($model->status != ReturPenjualan::STATUS_DRAFT) {
         $this->redirect(array('view', 'id' => $model->id));
      }

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      $returPenjualanDetail = new ReturPenjualanDetail('search');
      $returPenjualanDetail->unsetAttributes();
      $returPenjualanDetail->setAttribute('retur_penjualan_id', '='.$id);

      $barang = new Barang('search');
      $barang->unsetAttributes();
      if (isset($_GET['cariBarang'])) {
         $barang->setAttribute('nama', $_GET['namaBarang']);
      }

      /*
       * Grid untuk tampilan pemilihan nomor penjualan/struk
       */
      $penjualanDetail = new PenjualanDetail('search');
      $penjualanDetail->unsetAttributes();
      if (isset($_GET['PenjualanDetail'])) {
         $penjualanDetail->attributes = $_GET['PenjualanDetail'];
      }
      if (isset($_GET['pilih'])) {
         $barcode = $_GET['barcode'] == '' ? 'null' : $_GET['barcode'];
         $qty = $_GET['qty'];
         $penjualanDetail->setAttribute('barcode', '='.$barcode);
         $penjualanDetail->setAttribute('qty', '>='.$qty);
      }
      $penjualanDetail->setAttribute('statusPenjualan', '<>0');
//      $penjualanDetail->setAttribute('customerId', '='.$model->customer_id);

      $this->render('ubah', array(
          'model' => $model,
          'returPenjualanDetail' => $returPenjualanDetail,
          'barang' => $barang,
          'penjualanDetail' => $penjualanDetail
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
      $model = new ReturPenjualan('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['ReturPenjualan']))
         $model->attributes = $_GET['ReturPenjualan'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return ReturPenjualan the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = ReturPenjualan::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param ReturPenjualan $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'retur-penjualan-form') {
         echo CActiveForm::validate($model);
         Yii::app()->end();
      }
   }

   /**
    * Untuk render link actionView jika ada nomor, jika belum, string kosong
    * @param obj $data
    * @return string Link ke action view jika ada nomor
    */
   public function renderLinkToView($data) {
      $return = '';
      if (isset($data->nomor)) {
         $return = '<a href="'.
                 $this->createUrl('view', array('id' => $data->id)).'">'.
                 $data->nomor.'</a>';
      }
      return $return;
   }

   /**
    * render link actionUbah jika belum ada nomor
    * @param obj $data
    * @return string tanggal, beserta link jika masih draft (belum ada nomor)
    */
   public function renderLinkToUbah($data) {
      if (!isset($data->nomor)) {
         $return = '<a href="'.
                 $this->createUrl('ubah', array('id' => $data->id)).'">'.
                 $data->tanggal.'</a>';
      } else {
         $return = $data->tanggal;
      }
      return $return;
   }

   public function actionTambahDetail($id) {
      $return = array(
          'sukses' => false
      );
      if (isset($_POST['penjualanDetailId'])) {
         $penjualanDetailId = $_POST['penjualanDetailId'];
         $qty = $_POST['qty'];
         $penjualanDetail = PenjualanDetail::model()->findByPk($penjualanDetailId);

         $returPenjualanDetail = new ReturPenjualanDetail;
         $returPenjualanDetail->retur_penjualan_id = $id;
         $returPenjualanDetail->penjualan_detail_id = $penjualanDetailId;
         $returPenjualanDetail->qty = $qty;
         $returPenjualanDetail->harga_jual = $penjualanDetail->harga_jual;
         if ($returPenjualanDetail->save()) {
            $return = array(
                'sukses' => true
            );
         }
      }
      $this->renderJSON($return);
   }

   public function actionHapusDetail($id) {
      $detail = ReturPenjualanDetail::model()->findByPk($id);
      $detail->delete();
   }

   /*
    * Mengembalikan nilai total retur penjualan
    */

   public function actionTotal($id) {
      $returPenjualan = $this->loadModel($id);
      $total = $returPenjualan->getTotal();
      echo $total;
   }

   /*
    * Simpan Retur Penjualan
    * 1. Ubah Status Retur Penjualan
    * 2. Kurangi stock
    * 3. Create Hutang
    */

   public function actionSimpan($id) {
      $return = array('sukses' => false);
      // cek jika 'simpan' ada dan bernilai true
      if (isset($_POST['simpan']) && $_POST['simpan']) {
         $returPenjualan = $this->loadModel($id);
         if ($returPenjualan->status == 0) {
            /*
             * simpan retur penjualan jika hanya dan hanya jika status masih draft
             */
            if ($returPenjualan->simpan()) {
               $return = array('sukses' => true);
            }
         }
      }
      $this->renderJSON($return);
   }

//   public function renderRadioButton($data, $row) {
//      return CHtml::radioButton('penjualanid', $row == 0, array('value' => $data->id));
//   }
}
