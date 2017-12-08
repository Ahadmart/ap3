<?php

class ReturpenjualanController extends Controller
{

    const PROFIL_ALL = 0;
    const PROFIL_CUSTOMER = Profil::TIPE_CUSTOMER;
    /* ============== */
    const PRINT_RETUR_PENJUALAN = 0;

    /**
     * @return array action filters
     */
    public function filters()
    {
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
    public function accessRules()
    {
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
    public function actionView($id)
    {
        $returPenjualanDetail = new ReturPenjualanDetail('search');
        $returPenjualanDetail->unsetAttributes();
        $returPenjualanDetail->setAttribute('retur_penjualan_id', '=' . $id);

        $tipePrinterAvailable = array(Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER);

        $printerReturPenjualan = Device::model()->listDevices($tipePrinterAvailable);

        $kertasUntukPdf = ReturPenjualan::model()->listNamaKertas();

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'returPenjualanDetail' => $returPenjualanDetail,
            'printerReturPenjualan' => $printerReturPenjualan,
            'kertasUntukPdf' => $kertasUntukPdf
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
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
            'condition' => 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER,
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
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        if ($model->status != ReturPenjualan::STATUS_DRAFT) {
            $this->redirect(array('view', 'id' => $model->id));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $returPenjualanDetail = new ReturPenjualanDetail('search');
        $returPenjualanDetail->unsetAttributes();
        $returPenjualanDetail->setAttribute('retur_penjualan_id', '=' . $id);

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
            $penjualanDetail->setAttribute('barcode', '=' . $barcode);
            $penjualanDetail->setAttribute('qty', '>=' . $qty);
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
    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == ReturPenjualan::STATUS_DRAFT) {
            ReturPenjualanDetail::model()->deleteAll('retur_penjualan_id=:id', [':id' => $id]);
            $model->delete();
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
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
    public function loadModel($id)
    {
        $model = ReturPenjualan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ReturPenjualan $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
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
    public function renderLinkToView($data)
    {
        $return = '';
        if (isset($data->nomor)) {
            $return = '<a href="' .
                    $this->createUrl('view', array('id' => $data->id)) . '">' .
                    $data->nomor . '</a>';
        }
        return $return;
    }

    /**
     * render link actionUbah jika belum ada nomor
     * @param obj $data
     * @return string tanggal, beserta link jika masih draft (belum ada nomor)
     */
    public function renderLinkToUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
                    $this->createUrl('ubah', array('id' => $data->id)) . '">' .
                    $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

    public function actionTambahDetail($id)
    {
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

    public function actionHapusDetail($id)
    {
        $detail = ReturPenjualanDetail::model()->findByPk($id);
        $detail->delete();
    }

    /*
     * Mengembalikan nilai total retur penjualan
     */

    public function actionTotal($id)
    {
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

    public function actionSimpan($id)
    {
        $return = array('sukses' => false);
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $returPenjualan = $this->loadModel($id);
            if ($returPenjualan->status == ReturPenjualan::STATUS_DRAFT) {
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

    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        $condition = $tipe == Profil::TIPE_CUSTOMER ? 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER : 'id>' . Profil::AWAL_ID;
        $profilList = Profil::model()->findAll(array(
            'select' => 'id, nama',
            'condition' => $condition,
            'order' => 'nama'));
        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string.='<option value="' . $profil->id . '">';
            $string.=$profil->nama . '</option>';
        }
        echo $string;
    }

    public function getNamaFile($nomor, $print)
    {
        switch ($print) {
            case self::PRINT_RETUR_PENJUALAN:
                return "retur_penjualan-{$nomor}";
        }
    }

    public function getText($model, $print)
    {
        switch ($print) {
            case self::PRINT_RETUR_PENJUALAN:
                return $model->returPenjualanText();
        }
    }

    public function printLpr($id, $device, $print = 0)
    {
        $model = $this->loadModel($id);
        $text = $this->getText($model, $print);
        $device->printLpr($text);
        $this->renderPartial('_print_autoclose', array(
            'text' => $text
        ));
    }

    public function exportPdf($id, $kertas = ReturPenjualan::KERTAS_A4, $draft = false)
    {

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
         * Data Supplier
         */
        $profil = Profil::model()->findByPk($modelHeader->profil_id);

        /*
         * Retur Penjualan Detail
         */
        $returPenjualanDetail = ReturPenjualanDetail::model()->with('penjualanDetail', 'penjualanDetail.barang')->findAll(array(
            'condition' => "retur_penjualan_id={$id}",
            'order' => 'barang.nama'
        ));

        /*
         * Persiapan render PDF
         */
        $listNamaKertas = ReturPenjualan::listNamaKertas();
        $mPDF1 = Yii::app()->ePdf->mpdf('', $listNamaKertas[$kertas]);
        $viewCetak = '_pdf';
        if ($draft) {
            $viewCetak = '_pdf_draft';
        }
        $mPDF1->WriteHTML($this->renderPartial($viewCetak, array(
                    'modelHeader' => $modelHeader,
                    'branchConfig' => $branchConfig,
                    'profil' => $profil,
                    'returPenjualanDetail' => $returPenjualanDetail
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumSuffix = ' dari ';
        $mPDF1->pagenumPrefix = 'Halaman ';
        // Render PDF
        $mPDF1->Output("{$modelHeader->nomor}.pdf", 'I');
    }

    public function exportText($id, $device, $print = 0)
    {
        $model = $this->loadModel($id);
        $namaFile = $this->getNamaFile($model->nomor, $print);
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=\"{$namaFile}.text\"");
        header("Pragma: no-cache");
        header("Expire: 0");
        $text = $this->getText($model, $print);

        echo $device->revisiText($text);

        Yii::app()->end();
    }

    public function actionPrintReturPenjualan($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    $this->printLpr($id, $device);
                    break;
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    $this->exportPdf($id, $_GET['kertas']);
                    break;
                case Device::TIPE_TEXT_PRINTER:
                    $this->exportText($id, $device);
                    break;
            }
        }
    }

    public function actionImport()
    {
        $modelCsvForm = new UploadCsvReturPenjualanForm;
        $customerList = Profil::model()->profilTrx()->tipeCustomer()->orderByNama()->findAll(array(
            'select' => 'id, nama'
        ));
        if (isset($_POST['UploadCsvReturPenjualanForm'])) {
            $modelCsvForm->attributes = $_POST['UploadCsvReturPenjualanForm'];
            if (!empty($_FILES['UploadCsvReturPenjualanForm']['tmp_name']['csvFile'])) {
                $modelCsvForm->csvFile = CUploadedFile::getInstance($modelCsvForm, 'csvFile');
                $return = $modelCsvForm->simpanCsvKeReturPenjualan();
                if ($return['sukses']) {
                    $this->redirect($this->createUrl('ubah', ['id' => $return['returPenjualanId']]));
                }
            }
        }
        $this->render('import', [
            'modelCsvForm' => $modelCsvForm,
            'customerList' => $customerList
        ]);
    }

    public function actionCariByRef($profilId, $nomorRef, $nominal)
    {
        $pembelian = ReturPenjualan::model()->cariByRef($profilId, $nomorRef, $nominal);
        empty($pembelian) ? $this->renderJSON(['ada' => false]) : $this->renderJSON(['ada' => true, 'returpenjualan' => $this->renderPartial('_import_sudah_ada', ['pembelian' => $pembelian], true)]);
    }

}
