<?php

class ReturpembelianController extends Controller {

   const PROFIL_ALL = 0;
   const PROFIL_SUPPLIER = Profil::TIPE_SUPPLIER;

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

      $returPembelianDetail = new ReturPembelianDetail('search');
      $returPembelianDetail->unsetAttributes();
      $returPembelianDetail->setAttribute('retur_pembelian_id', '='.$id);

      $this->render('view', array(
          'model' => $this->loadModel($id),
          'returPembelianDetail' => $returPembelianDetail,
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'ubah' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new ReturPembelian;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['ReturPembelian'])) {
         $model->attributes = $_POST['ReturPembelian'];
         if ($model->save())
            $this->redirect(array('ubah', 'id' => $model->id));
      }

      $supplierList = Profil::model()->findAll(array(
          'select' => 'id, nama',
          'condition' => 'id>'.Profil::AWAL_ID.' and tipe_id='.Profil::TIPE_SUPPLIER,
          'order' => 'nama'));

      $this->render('tambah', array(
          'model' => $model,
          'supplierList' => $supplierList
      ));
   }

   /**
    * Updates a particular model.
    * @param integer $id the ID of the model to be updated
    */
   public function actionUbah($id) {
      $model = $this->loadModel($id);

      /* Hanya ubah jika dan hanya jika statusnya masih DRAFT */
      if ($model->status != ReturPembelian::STATUS_DRAFT) {
         $this->redirect(array('view', 'id' => $model->id));
      }
      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['ReturPembelian'])) {
         $model->attributes = $_POST['ReturPembelian'];
         if ($model->save())
            $this->redirect(array('view', 'id' => $id));
      }

      /*
       * Untuk menampilkan dropdown barang sort by barcode;
       */
      $barcode = SupplierBarang::model()->ambilBarangBarcodePerSupplier($model->profil_id);
      $barangBarcode = array();
      foreach ($barcode as $barang) {
         $barangBarcode[$barang['id']] = "{$barang['barcode']} ({$barang['nama']})";
      }

      /*
       * Untuk menampilkan dropdown barang sort by nama;
       */
      $nama = SupplierBarang::model()->ambilBarangNamaPerSupplier($model->profil_id);
      $barangNama = array();
      foreach ($nama as $barang) {
         $barangNama[$barang['id']] = "{$barang['nama']} ({$barang['barcode']})";
      }

      $inventoryBalance = new InventoryBalance('search');
      $inventoryBalance->unsetAttributes();
      if (isset($_GET['ajax']) && $_GET['ajax'] === 'inventory-balance-grid' && isset($_POST['barangId'])) {
         $barangId = $_POST['barangId'];
         $inventoryBalance->setAttribute('barang_id', '='.$barangId);
         $inventoryBalance->setAttribute('qty', '<>0');
      } else {
         $inventoryBalance->setAttribute('barang_id', '=0');
      }

      $returPembelianDetail = new ReturPembelianDetail('search');
      $returPembelianDetail->unsetAttributes();
      $returPembelianDetail->setAttribute('retur_pembelian_id', '='.$id);

      $this->render('ubah', array(
          'model' => $model,
          'barangBarcode' => $barangBarcode,
          'barangNama' => $barangNama,
          'inventoryBalance' => $inventoryBalance,
          'returPembelianDetail' => $returPembelianDetail
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
      $model = new ReturPembelian('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['ReturPembelian']))
         $model->attributes = $_GET['ReturPembelian'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return ReturPembelian the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = ReturPembelian::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param ReturPembelian $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'retur-pembelian-form') {
         echo CActiveForm::validate($model);
         Yii::app()->end();
      }
   }

   public function renderLinkToView($data) {
      $return = '';
      if (isset($data->nomor)) {
         $return = '<a href="'.
                 $this->createUrl('view', array('id' => $data->id)).'">'.
                 $data->nomor.'</a>';
      }
      return $return;
   }

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

   function renderLinkToSupplier($data) {
      return '<a href="'.
              $this->createUrl('supplier/view', array('id' => $data->profil_id)).'">'.
              $data->profil->nama.'</a>';
   }

   public function renderRadioButton($data, $row) {
      return CHtml::radioButton('invid', $row == 0, array('value' => $data->id));
   }

   /*
    * Mengembalikan barcode, nama, beserta stok barang
    */

   public function actionGetBarangInfo($id) {
      if (isset($id)) {
         $barang = Barang::model()->findByPk($id);
         $stock = InventoryBalance::model()->find(array('select' => 'sum(qty) jumlah', 'condition' => 'barang_id=:barangId', 'params' => array(':barangId' => $id)));
         echo "<small>$barang->barcode</small> $barang->nama  <small>Stok</small> $stock->jumlah";
      }
   }

   /**
    * Cari Barang untuk autocomplete.
    * Fixme: tambahkan profil_id,
    * @param text $term Text yang akan di cari
    */
   public function actionCariBarang($profilId, $term) {
      $barangs = Barang::model()->findAll("status=1 and concat(barcode, nama) like :term", array(':term' => "%{$term}%"));

      $hasilBarang = array();
      foreach ($barangs as $barang) {
         $hasilBarang[] = array(
             'id' => $barang->id,
             'label' => "{$barang->nama} ({$barang->barcode})",
             'value' => $barang->nama,
         );
      }

      $this->renderJSON($hasilBarang);
   }

   public function actionPilihInv($id) {

      $inventoryBalanceId = $_POST['invid'];
      $returQty = $_POST['retur-qty'];

      //$model = $this->loadModel($id);

      $detail = new ReturPembelianDetail;
      $detail->retur_pembelian_id = $id;
      $detail->inventory_balance_id = $inventoryBalanceId;
      $detail->qty = $returQty;
      $detail->save();
   }

   /*
    * Update qty retur pembelian detail, via ajax
    */

   public function actionUpdateQty() {
      $return = array('sukses' => false);
      if (isset($_POST['pk'])) {
         $pk = $_POST['pk'];
         $value = $_POST['value'];
         if ($value > 0) {
            $returPembelianDetail = ReturPembelianDetail::model()->findByPk($pk);

            $returPembelianDetail->qty = $value;
            if ($returPembelianDetail->save()) {
               $return = array('sukses' => true);
            }
         }
      }
      $this->renderJSON($return);
   }

   /*
    * Mengembalikan nilai total retur pembelian
    */

   public function actionTotal($id) {
      $returPembelian = $this->loadModel($id);
      $total = $returPembelian->getTotal();
      echo $total;
   }

   /*
    * Hapus Retur Pembelian Detail, via ajax
    */

   public function actionHapusDetail($id) {
      if (isset($_GET['ajax']) && $_GET['ajax'] === 'retur-pembelian-detail-grid') {
         $returPembelianDetail = ReturPembelianDetail::model()->findByPk($id);
         $returPembelianDetail->delete();
      }
   }

   /*
    * Simpan Retur Pembelian
    * 1. Ubah Status Retur Pembelian
    * 2. Kurangi stock
    * 3. Create Piutang
    */

   public function actionSimpan($id) {
      $return = array('sukses' => false);
      // cek jika 'simpan' ada dan bernilai true
      if (isset($_POST['simpan']) && $_POST['simpan']) {
         $returPembelian = $this->loadModel($id);
         if ($returPembelian->status == 0) {
            /*
             * simpan retur pembelian jika hanya dan hanya jika status masih draft
             */
            if ($returPembelian->simpanReturPembelian()) {
               $return = array('sukses' => true);
            }
         }
      }
      echo $this->renderJSON($return);
   }

   public function actionAmbilProfil($tipe) {
      /*
       * Tampilkan daftar sesuai pilihan tipe
       */
      $condition = $tipe == Profil::TIPE_SUPPLIER ? 'id>'.Profil::AWAL_ID.' and tipe_id='.Profil::TIPE_SUPPLIER : 'id>'.Profil::AWAL_ID;
      $profilList = Profil::model()->findAll(array(
          'select' => 'id, nama',
          'condition' => $condition,
          'order' => 'nama'));
      /* FIX ME: Pindahkan ke view */
      $string = '<option>Pilih satu..</option>';
      foreach ($profilList as $profil) {
         $string.='<option value="'.$profil->id.'">';
         $string.=$profil->nama.'</option>';
      }
      echo $string;
   }

}
