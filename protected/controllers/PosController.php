<?php

class PosController extends Controller {

   public $layout = '//layouts/pos_column3';

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
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
   public function actionTambah() {
      $model = new Penjualan;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      $model->profil_id = Profil::PROFIL_UMUM;

      if ($model->save())
         $this->redirect(array('ubah', 'id' => $model->id));

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

      $penjualanDetail = new PenjualanDetail('search');
      $penjualanDetail->unsetAttributes();
      $penjualanDetail->setAttribute('penjualan_id', '='.$id);

      $this->render('ubah', array(
          'model' => $model,
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
      $model = new Penjualan('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['Penjualan']))
         $model->attributes = $_GET['Penjualan'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Penjualan the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = Penjualan::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param Penjualan $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'penjualan-form') {
         echo CActiveForm::validate($model);
         Yii::app()->end();
      }
   }

   public function actionCariBarang($term) {
      $arrTerm = explode(' ', $term);
      $wBarcode = '(';
      $wNama = '(';
      $pBarcode = array();
      $param = array();
      $firstRow = true;
      $i = 1;
      foreach ($arrTerm as $bTerm) {
         if (!$firstRow) {
            $wBarcode.=' AND ';
            $wNama.=' AND ';
         }
         $wBarcode.="barcode like :term{$i}";
         $wNama.="nama like :term{$i}";
         $param[":term{$i}"] = "%{$bTerm}%";
         $firstRow = FALSE;
         $i++;
      }
      $wBarcode .= ')';
      $wNama .= ')';
//      echo $wBarcode.' AND '.$wNama;
//      print_r($param);

      $q = new CDbCriteria();
      $q->addCondition("{$wBarcode} OR {$wNama}");
      $q->params = $param;
      $barangs = Barang::model()->findAll($q);

      $r = array();
      foreach ($barangs as $barang) {
         $r[] = array(
             'label' => $barang->nama,
             'value' => $barang->barcode,
             'stok' => is_null($barang->stok) ? 'null' : $barang->stok,
             'harga' => $barang->hargaJual
         );
      }

      $this->renderJSON($r);
   }

   /**
    * Tambah barang jual
    * @param int $id ID Penjualan
    * @return JSON boolean sukses, array error[code, msg]
    */
   public function actionTambahBarang($id) {
      $return = array(
          'sukses' => false,
          'error' => array(
              'code' => '500',
              'msg' => 'Sempurnakan input!',
          )
      );
      if (isset($_POST['barcode'])) {
         $penjualan = $this->loadModel($id);
         $barcode = $_POST['barcode'];
         $return = $penjualan->tambahBarang($barcode, 1);
      }
      $this->renderJSON($return);
   }

   public function actionKembalian() {
      echo ($_POST['bayar'] - $_POST['total']) < 0 ? '&nbsp' : number_format($_POST['bayar'] - $_POST['total'], 0, ',', '.');
   }

   public function renderQtyLinkEditable($data, $row) {
      $ak = '';
      if ($row == 0) {
         $ak = 'accesskey="q"';
      }
      return '<a href="#" class="editable-qty" data-type="text" data-pk="'.$data->id.'" '.$ak.' data-url="'.
              Yii::app()->controller->createUrl('updateqty').'">'.
              $data->qty.'</a>';
   }

   /**
    * Update qty detail pembelian via ajax
    */
   public function actionUpdateQty() {
      if (isset($_POST['pk'])) {
         $pk = $_POST['pk'];
         $qty = $_POST['value'];
         $detail = PenjualanDetail::model()->findByPk($pk);
         if ($qty > 0) {
            $detail->qty = $qty;

            $return = array('sukses' => false);
            if ($detail->save()) {
               
            }
         } else {
            $detail->delete();
         }
         $return = array('sukses' => true);
         $this->renderJSON($return);
      }
   }

   public function actionSuspended() {
      $model = new Penjualan('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['Penjualan'])) {
         $model->attributes = $_GET['Penjualan'];
      }
      $model->status = '='.Penjualan::STATUS_DRAFT;

      $this->render('suspended', array(
          'model' => $model,
      ));
   }

   /**
    * render link actionUbah
    * @param obj $data
    * @return string tanggal, beserta link
    */
   public function renderLinkToUbah($data) {
      $return = '<a href="'.
              $this->createUrl('ubah', array('id' => $data->id)).'">'.
              $data->tanggal.'</a>';

      return $return;
   }

}
