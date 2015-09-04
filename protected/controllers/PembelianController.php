<?php

class PembelianController extends Controller {

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
      $pembelianDetail = new PembelianDetail('search');
      $pembelianDetail->unsetAttributes();
      $pembelianDetail->setAttribute('pembelian_id', '='.$id);
      if (isset($_GET['PembelianDetail'])) {
         $pembelianDetail->attributes = $_GET['PembelianDetail'];
      }

      $this->render('view', array(
          'model' => $this->loadModel($id),
          'pembelianDetail' => $pembelianDetail
      ));
   }

   /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'ubah' page.
    */
   public function actionTambah() {
      $this->layout = '//layouts/box_kecil';
      $model = new Pembelian;

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Pembelian'])) {
         $model->attributes = $_POST['Pembelian'];
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

   /**
    * Updates a particular model.
    * @param integer $id the ID of the model to be updated
    */
   public function actionUbah($id) {
      $model = $this->loadModel($id);

      // Jika pembelian sudah disimpan (status bukan draft) maka tidak bisa diubah lagi
      if ($model->status != Pembelian::STATUS_DRAFT) {
         $this->redirect(array('view', 'id' => $id));
      }

      // Uncomment the following line if AJAX validation is needed
      // $this->performAjaxValidation($model);

      if (isset($_POST['Pembelian'])) {
         $model->attributes = $_POST['Pembelian'];
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

      $pembelianDetail = new PembelianDetail('search');
      $pembelianDetail->unsetAttributes();
      $pembelianDetail->setAttribute('pembelian_id', '='.$id);
      if (isset($_GET['PembelianDetail'])) {
         $pembelianDetail->attributes = $_GET['PembelianDetail'];
      }

      /* Model untuk membuat barang baru */
      $barang = new Barang;

      $this->render('ubah', array(
          'model' => $model,
          'barangBarcode' => $barangBarcode,
          'barangNama' => $barangNama,
          'pembelianDetail' => $pembelianDetail,
          'barang' => $barang
              //'totalPembelian' => $model->ambilTotal()
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
      $model = new Pembelian('search');
      $model->unsetAttributes();  // clear any default values
      if (isset($_GET['Pembelian']))
         $model->attributes = $_GET['Pembelian'];

      $this->render('index', array(
          'model' => $model,
      ));
   }

   /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer $id the ID of the model to be loaded
    * @return Pembelian the loaded model
    * @throws CHttpException
    */
   public function loadModel($id) {
      $model = Pembelian::model()->findByPk($id);
      if ($model === null)
         throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
   }

   /**
    * Performs the AJAX validation.
    * @param Pembelian $model the model to be validated
    */
   protected function performAjaxValidation($model) {
      if (isset($_POST['ajax']) && $_POST['ajax'] === 'pembelian-form') {
         echo CActiveForm::validate($model);
         Yii::app()->end();
      }
   }

   /**
    * Untuk mengambil informasi barang untuk ditampilkan
    * pada saat input pembelian barang
    */
   public function actionGetBarang() {
      if (isset($_POST['barangId'])) {
         $barangId = $_POST['barangId'];
         $barang = Pembelian::model()->ambilDataBarang($barangId);
         $arr = array(
             'barangId' => $barangId,
             'nama' => $barang['nama'],
             'barcode' => $barang['barcode'],
             'labelHargaBeli' => number_format($barang['harga_beli'], 0, ',', '.'),
             'hargaBeli' => number_format($barang['harga_beli'], 0, '', ''),
             'labelHargaJual' => number_format($barang['harga_jual'], 0, ',', '.'),
             'hargaJual' => number_format($barang['harga_jual'], 0, '', ''),
             'labelRrp' => number_format($barang['rrp'], 0, ',', '.'),
             'rrp' => number_format($barang['rrp'], 0, '', ''),
             'satuan' => $barang['satuan'],
         );
         echo CJSON::encode($arr);
      }
   }

   public function actionTambahBarang($id) {
      // Jika ada post input-detail, berarti ada input-an barang
      if (isset($_POST['input-detail']) && $_POST['input-detail'] == 1) {
         $detail = new PembelianDetail;
         $detail->pembelian_id = $id;
         $detail->barang_id = $_POST['barang-id'];
         $detail->qty = $_POST['qty'] > 0 ? $_POST['qty'] : 0;
         $detail->harga_beli = $_POST['hargabeli'];
         $detail->tanggal_kadaluwarsa = $_POST['tanggal_kadaluwarsa'];
         $detail->harga_jual = $_POST['hargajual'];
         $detail->harga_jual_rekomendasi = $_POST['rrp'];

         // echo $id.' '.$_POST['barang-id'].' '.$_POST['qty'].' '.$_POST['tanggal_kadaluwarsa'].' '.$_POST['hargabeli'];
         // echo terlihat di console
         if ($detail->save()) {
            //HargaJualBarang::model()->updateHargaJual($_POST['barang-id'], $inputHargaJual);
            echo 'berhasil';
         } else {
            echo 'gagal';
         }
      }
   }

   /**
    * Hapus detail pembelian
    * @param integer $id the ID of the detail to be deleted
    */
   public function actionHapusDetail($id) {
      $detail = PembelianDetail::model()->findByPk($id);
      $detail->delete();
   }

   /**
    * Nilai total pembelian dalam text terformat ribuan
    * @param int $id
    */
   public function actionTotal($id) {
      $pembelian = $this->loadModel($id);
      $total = array();
      $total['sukses'] = true;
      $total['totalF'] = number_format($pembelian->ambilTotal(), 0, ',', '.');

      $this->renderJSON($total);
   }

   /**
    * Update qty detail pembelian via ajax
    */
   public function actionUpdateQty() {
      if (isset($_POST['pk'])) {
         $pk = $_POST['pk'];
         $qty = $_POST['value'];
         $detail = PembelianDetail::model()->findByPk($pk);
         $detail->qty = $qty;

         $return = array('sukses' => false);
         if ($detail->save()) {
            $return = array('sukses' => true);
         }

         $this->renderJSON($return);
      }
   }

   /**
    * Simpan pembelian:
    * 1. update status dari draft menjadi pembelian
    * 2. update stock
    * 3. update harga jual
    * 4. update stok minus
    * @param int $id
    */
   public function actionSimpanPembelian($id) {
      $return = array('sukses' => false);
      // cek jika 'simpan' ada dan bernilai true
      if (isset($_POST['simpan']) && $_POST['simpan']) {
         $pembelian = $this->loadModel($id);
         if ($pembelian->status == 0) {
            /*
             * simpan pembelian jika hanya dan hanya jika status masih draft
             */
            $return = $pembelian->simpanPembelian();
         }
      }
      $this->renderJSON($return);
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

   function actionTambahBarangBaru($id) {
      $return = array(
          'sukses' => false
      );
      $model = $this->loadModel($id);
      if (isset($_POST['Barang'])) {
         $barang = new Barang;
         $barang->attributes = $_POST['Barang'];
         if ($barang->save()) {
            $supplierBarang = new SupplierBarang;
            $supplierBarang->supplier_id = $model->profil_id;
            $supplierBarang->barang_id = $barang->id;
            if ($supplierBarang->save()) {
               $return = array(
                   'sukses' => true,
                   'barangId' => $barang->id,
                   'barcode' => $barang->barcode,
                   'nama' => $barang->nama,
                   'satuan' => $barang->satuan->nama
               );
            } else {
               /* Jika error simpan supplier, barang hapus saja, emulate roolback */
               $barang->delete();
            }
         } else {
            $return['msg'] = 'Gagal simpan! barcode sudah ada?';
         }
      }
      $this->renderJSON($return);
   }

   public function actionImport() {
      if (isset($_POST['nomor'])) {
         $dbGudang = 'gudang';
         $nomor = $_POST['nomor'];
         $pembelianPos2 = Yii::app()->db
                 ->createCommand("
                     SELECT tb.tglTransaksiBeli, s.namaSupplier
                     FROM {$dbGudang}.transaksibeli tb 
                     JOIN {$dbGudang}.supplier s on tb.idSupplier = s.idSupplier
                     WHERE idTransaksiBeli = :nomor")
                 ->bindValue(':nomor', $nomor)
                 ->queryRow();
         // print_r($pembelianPos2);
         $profil = Profil::model()->find('nama=:nama', array('nama' => trim($pembelianPos2['namaSupplier'])));
         // print_r($supplier);
         if (!is_nul($profil)) {
            $pembelian = new Pembelian;
            $pembelian->profil_id = $profil->id;
            $pembelian->referensi = $nomor;
            $pembelian->tanggal_referensi = $pembelianPos2['tglTransaksiBeli'];
            if ($pembelian->save()) {

               $pembelianDetailPos2 = Yii::app()->db
                       ->createCommand("
                           select db.barcode, hargaBeli, gb.hargaJual, RRP, jumBarangAsli, tglExpire, barang.id
                           from gudang.detail_beli db
                           join gudang.barang gb on db.barcode = gb.barcode
                           left join barang on db.barcode = barang.barcode
                           where idTransaksiBeli = :nomor
                               ")
                       ->bindValue(':nomor', $nomor)
                       ->queryAll();

               foreach ($pembelianDetailPos2 as $detailPos2) {
                  // Jika barang.id belum ada, buat data barang baru
                  $barangId = $detailPos2['id'];
                  if (is_null($detailPos2['id'])) {
                     // Fix Me: Buat data barang baru
                  }
                  $detail = new PembelianDetail;
                  $detail->pembelian_id = $pembelian->id;
                  $detail->barang_id = $barangId;
                  $detail->harga_beli = $detailPos2['hargaBeli'];
                  $detail->harga_jual = $detailPos2['hargaJual'];
                  $detail->harga_jual_rekomendasi = $detailPos2['RRP'];
                  $detail->tanggal_kadaluwarsa = $detailPos2['tglExpire'];
                  $detail->save();
               }
               $this->redirect('index');
            }
         }
      }
      $this->render('import');
   }

}
