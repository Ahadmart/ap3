<?php

class PenjualanController extends Controller {

   const PROFIL_ALL = 0;
   const PROFIL_CUSTOMER = Profil::TIPE_CUSTOMER;

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
      $penjualanDetail = new PenjualanDetail('search');
      $penjualanDetail->unsetAttributes();
      $penjualanDetail->setAttribute('penjualan_id', '='.$id);
      if (isset($_GET['PenjualanDetail'])) {
         $penjualanDetail->attributes = $_GET['PenjualanDetail'];
      }

      $this->render('view', array(
          'model' => $this->loadModel($id),
          'penjualanDetail' => $penjualanDetail
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new Penjualan;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Penjualan'])) {
         $model->attributes = $_POST['Penjualan'];
         if ($model->save())
            $this->redirect(array('ubah', 'id' => $model->id));
      }

      $customerList = Profil::model()->findAll(array(
          'select' => 'id, nama',
          'condition' => 'id>'.Profil::AWAL_ID.' and tipe_id='.Profil::TIPE_CUSTOMER,
          'order' => 'nama'));

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

      // Jika status sudah tidak draft, tidak bisa ubah
      if ($model->status != Penjualan::STATUS_DRAFT) {
         $this->redirect(array('view', 'id' => $id));
      }

      $model->scenario = 'tampil';

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Penjualan'])) {
         $model->attributes = $_POST['Penjualan'];
         if ($model->save())
            $this->redirect(array('view', 'id' => $id));
      }

      $penjualanDetail = new PenjualanDetail('search');
      $penjualanDetail->unsetAttributes();
      $penjualanDetail->setAttribute('penjualan_id', '='.$id);

      $barang = new Barang('search');
      $barang->unsetAttributes();

      if (isset($_GET['cariBarang'])) {
         $barang->setAttribute('nama', $_GET['namaBarang']);
      }

      $this->render('ubah', array(
          'model' => $model,
          'penjualanDetail' => $penjualanDetail,
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
      $model = new Penjualan('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['Penjualan'])) {
         $model->attributes = $_GET['Penjualan'];
      }

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

   /**
    * Tambah barang jual
    * @param int $id ID Penjualan
    * @return JSON boolean sukses, array error[code, msg]
    */
   public function actionTambahDetail($id) {
      $return = array(
          'sukses' => false,
          'error' => array(
              'code' => '500',
              'msg' => 'Sempurnakan input!',
          )
      );
      if (isset($_POST['tambah_barang']) && $_POST['tambah_barang']) {
         $penjualan = $this->loadModel($id);
         $qty = $_POST['qty'];
         $barcode = $_POST['barcode'];
         $return = $penjualan->tambahBarang($barcode, $qty);
      }
      $this->renderJSON($return);
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

   public function actionSimpanPenjualan($id) {
      $return = array(
          'sukses' => false,
          'error' => array(
              'code' => '500',
              'msg' => 'Sempurnakan input!',
          )
      );
      if (isset($_POST['simpan']) && $_POST['simpan']) {
         $penjualan = $this->loadModel($id);
         $return = $penjualan->simpan();
      }
      $this->renderJSON($return);
   }

   public function actionHapusDetail($id) {
      $detail = PenjualanDetail::model()->findByPk($id);
      if (!$detail->delete()) {
         throw new Exception('Gagal hapus detail penjualan');
      }
   }

   /**
    * Ambil total penjualan via ajax
    */
   public function actionTotal($id) {
      $penjualan = $this->loadModel($id);
      $penjualan->scenario = 'tampil';
      $total = $penjualan->total;
      $return['sukses'] = true;
      $return['totalF'] = $total;
      echo CJSON::encode($return);
   }

   public function formatHargaJual($data) {
      return number_format($data->harga_jual, 0, ',', '.');
   }

   public function formatHargaJualRekomendasi($data) {
      return number_format($data->harga_jual_rekomendasi, 0, ',', '.');
   }

   /**
    * Render Faktur dalam format PDF
    * @param int $id penjualan ID
    */
   public function actionFaktur($id) {

      $modelHeader = $this->loadModel($id);
      $configs = Config::model()->findAll();
      /*
       * Ubah config (object) jadi array
       */
      $branchConfig = array();
      foreach ($configs as $config) {
         $branchConfig[$config->nama] = $config->nilai;
      }

      /*
       * Data Customer
       */
      $customer = Profil::model()->findByPk($modelHeader->profil_id);

      /*
       * Penjualan Detail
       */
      $penjualanDetail = PenjualanDetail::model()->with('barang')->findAll(array(
          'condition' => "penjualan_id={$id}",
          'order' => 'barang.nama'
      ));

      /*
       * Persiapan render PDF
       */
      $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
      $mPDF1->WriteHTML($this->renderPartial('_faktur', array(
                  'modelHeader' => $modelHeader,
                  'branchConfig' => $branchConfig,
                  'customer' => $customer,
                  'penjualanDetail' => $penjualanDetail
                      ), true
      ));

      $mPDF1->SetDisplayMode('fullpage');
      $mPDF1->pagenumSuffix = ' dari ';
      $mPDF1->pagenumPrefix = 'Halaman ';
      // Render PDF
      $mPDF1->Output("{$modelHeader->nomor}.pdf", 'I');
   }

   /**
    * Render csv untuk didownload
    * @param int $id penjualan ID
    */
   public function actionEksporCsv($id) {
      $model = $this->loadModel($id);
      $csv = $model->eksporCsv();

      $timeStamp = date("Y-m-d--H-i");
      $namaFile = "{$model->nomor}-{$model->profil->nama}-{$timeStamp}";

      $this->renderPartial('_csv', array(
          'namaFile' => $namaFile,
          'csv' => $csv
      ));
   }

   public function actionAmbilProfil($tipe) {
      /*
       * Tampilkan daftar sesuai pilihan tipe
       */
      $condition = $tipe == Profil::TIPE_CUSTOMER ? 'id>'.Profil::AWAL_ID.' and tipe_id='.Profil::TIPE_CUSTOMER : 'id>'.Profil::AWAL_ID;
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
