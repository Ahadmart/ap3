<?php

class StockopnameController extends Controller {

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
      $this->render('view', array(
          'model' => $this->loadModel($id),
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'ubah' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new StockOpname;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['StockOpname'])) {
         $model->attributes = $_POST['StockOpname'];
         if ($model->save())
            $this->redirect(array('ubah', 'id' => $model->id));
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
      $model = $this->loadModel($id);

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      /* Ini tidak akan terjadi
        if (isset($_POST['StockOpname'])) {
        $model->attributes = $_POST['StockOpname'];
        if ($model->save())
        $this->redirect(array('view', 'id' => $id));
        }
       * 
       */
      if ($model->status != StockOpname::STATUS_DRAFT) {
         $this->redirect(array('view', 'id' => $model->id));
      }

      $soDetail = new StockOpnameDetail('search');
      $soDetail->unsetAttributes();
      if (isset($_GET['StockOpnameDetail'])) {
         $soDetail->attributes = $_GET['StockOpnameDetail'];
      }
      $soDetail->setAttribute('stock_opname_id', "{$id}");

      $barang = new Barang('search');
      $barang->unsetAttributes();
      if (isset($_GET['cariBarang'])) {
         $barang->setAttribute('nama', $_GET['namaBarang']);
      }

      $this->render('ubah', array(
          'model' => $model,
          'soDetail' => $soDetail,
          'barang' => $barang
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
      $model = new StockOpname('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['StockOpname']))
         $model->attributes = $_GET['StockOpname'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return StockOpname the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = StockOpname::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param StockOpname $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-opname-form') {
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

   public function actionScanBarcode($id) {
      $return = array(
          'sukses' => false
      );
      if (isset($_POST['scan'])) {
         $barcode = $_POST['barcode'];
         $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));
         $qtySudahSo = StockOpnameDetail::model()->qtyYangSudahSo($id, $barang->id);
         $return = array(
             'sukses' => true,
             'barcode' => $barcode,
             'nama' => $barang->nama,
             'stok' => $barang->getStok(),
             'qtySudahSo' => $qtySudahSo
         );
      }

      $this->renderJSON($return);
   }

   public function actionTambahDetail($id) {
      $return = array(
          'sukses' => false
      );
      if (isset($_POST['tambah'])) {
         $barcode = $_POST['barcode'];
         $qty = $_POST['qty'];
         $barang = Barang::model()->find('barcode=:barcode', array(':barcode' => $barcode));

         $detail = new StockOpnameDetail;
         $detail->stock_opname_id = $id;
         $detail->barang_id = $barang->id;
         $detail->qty_tercatat = $barang->getStok();
         $detail->qty_sebenarnya = $qty;
         if ($detail->save()) {
            $return = array(
                'sukses' => true,
            );
         }
      }
      $this->renderJSON($return);
   }

   public function actionHapusDetail($id) {
      $detail = StockOpnameDetail::model()->findByPk($id);
      if (!$detail->delete()) {
         throw new Exception('Gagal hapus detail SO');
      }
   }

   /*
    * Simpan Stock Opname dan proses terkait (inventory)
    */

   public function actionSimpanSo($id) {
      $return = array('sukses' => false);
      // cek jika 'simpan' ada dan bernilai true
      if (isset($_POST['simpan']) && $_POST['simpan']) {
         $so = $this->loadModel($id);
         $return = $so->simpanSo();
      }
      echo $this->renderJSON($return);
   }

}
