<?php

class PenjualanController extends Controller
{

    const PROFIL_ALL = 0;
    const PROFIL_CUSTOMER = Profil::TIPE_CUSTOMER;
    /* ============== */
    const PRINT_INVOICE = 0;
    const PRINT_STRUK = 1;
    const PRINT_NOTA = 2;

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
        $penjualanDetail = new PenjualanDetail('search');
        $penjualanDetail->unsetAttributes();
        $penjualanDetail->setAttribute('penjualan_id', '=' . $id);
        if (isset($_GET['PenjualanDetail'])) {
            $penjualanDetail->attributes = $_GET['PenjualanDetail'];
        }

        $tipePrinterInvoiceRrp = array(Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER);
        $tipePrinterStruk = array(Device::TIPE_LPR, Device::TIPE_TEXT_PRINTER);
        $tipePrinterNota = array(Device::TIPE_LPR, Device::TIPE_TEXT_PRINTER);

        $printerInvoiceRrp = Device::model()->listDevices($tipePrinterInvoiceRrp);
        $printerStruk = Device::model()->listDevices($tipePrinterStruk);
        $printerNota = Device::model()->listDevices($tipePrinterNota);

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'penjualanDetail' => $penjualanDetail,
            'printerInvoiceRrp' => $printerInvoiceRrp,
            'printerStruk' => $printerStruk,
            'printerNota' => $printerNota
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
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
            'condition' => 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER,
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
    public function actionUbah($id)
    {
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
        $penjualanDetail->setAttribute('penjualan_id', '=' . $id);

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
    public function actionHapus($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
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
    public function loadModel($id)
    {
        $model = Penjualan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Penjualan $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
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
    public function actionTambahDetail($id)
    {
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

    public function actionSimpanPenjualan($id)
    {
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

    public function actionHapusDetail($id)
    {
        $detail = PenjualanDetail::model()->findByPk($id);
        PenjualanDiskon::model()->deleteAll('penjualan_detail_id=' . $detail->id);
        if (!$detail->delete()) {
            throw new Exception('Gagal hapus detail penjualan');
        }
    }

    /**
     * Ambil total penjualan via ajax
     */
    public function actionTotal($id)
    {
        $penjualan = $this->loadModel($id);
        $total = $penjualan->ambilTotal();
        $totalF = $penjualan->total;
        $return['sukses'] = true;
        $return['total'] = $total;
        $return['totalF'] = $totalF;
        $this->renderJSON($return);
    }

    public function formatHargaJual($data)
    {
        return number_format($data->harga_jual, 0, ',', '.');
    }

    public function formatHargaJualRekomendasi($data)
    {
        return number_format($data->harga_jual_rekomendasi, 0, ',', '.');
    }

    /**
     * Render Faktur/Invoice dalam format PDF
     * @param int $id penjualan ID
     */
    public function exportPdf($id)
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
        $mPDF1->WriteHTML($this->renderPartial('_invoice', array(
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
    public function actionExportCsv($id)
    {
        $model = $this->loadModel($id);
        $csv = $model->eksporCsv();

        $timeStamp = date("Y-m-d--H-i");
        $namaFile = "{$model->nomor}-{$model->profil->nama}-{$timeStamp}";

        $this->renderPartial('_csv', array(
            'namaFile' => $namaFile,
            'csv' => $csv
        ));
    }

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

    public function getNamaFile($nomor, $print)
    {
        switch ($print) {
            case self::PRINT_INVOICE:
                return "invoice-{$nomor}";
            case self::PRINT_STRUK:
                return "struk-{$nomor}";
            case self::PRINT_NOTA:
                return "nota-{$nomor}";
        }
    }

    public function getText($model, $print)
    {
        switch ($print) {
            case self::PRINT_INVOICE:
                return $model->invoiceText();
            case self::PRINT_STRUK:
                return $model->strukText();
            case self::PRINT_NOTA:
                return $model->notaText();
        }
    }

    public function printLpr($id, $device, $print = 0)
    {
        $model = $this->loadModel($id);
        $text = $this->getText($model, $print);
        $device->printLpr($text);
        $this->renderPartial('_print_autoclose');
    }

    public function actionPrintInvoice($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    $this->printLpr($id, $device);
                    break;
                case Device::TIPE_PDF_PRINTER:
                    $this->exportPdf($id);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->eksporCsv($id);
                    break;
                case Device::TIPE_TEXT_PRINTER:
                    $this->exportText($id, $device);
                    break;
            }
        }
    }

    public function actionImport()
    {
        if (isset($_POST['nomor'])) {
            $dbGudang = 'gudang';
            $nomor = $_POST['nomor'];
            $penjualanPos2 = Yii::app()->db->createCommand("
                     SELECT t.tglTransaksiJual, c.namaCustomer
                     FROM {$dbGudang}.transaksijual t
                     JOIN {$dbGudang}.customer c on t.idCustomer=c.idCustomer
                     WHERE idTransaksiJual = :nomor")
                    ->bindValue(':nomor', $nomor)
                    ->queryRow();
            $profil = Profil::model()->find('nama=:nama', array('nama' => trim($penjualanPos2['namaCustomer'])));
            if (!is_null($profil)) {
                $penjualan = new Penjualan;
                $penjualan->profil_id = $profil->id;
                if ($penjualan->save()) {

                    $penjualanDetailPos2 = Yii::app()->db
                            ->createCommand("
                           select d.barcode, d.jumBarang, d.hargaBeli, d.hargaJual, d.RRP, barang.id
                           from gudang.detail_jual d
                           join barang on d.barcode=barang.barcode
                           where d.nomorStruk = :nomor
                               ")
                            ->bindValue(':nomor', $nomor)
                            ->queryAll();

                    foreach ($penjualanDetailPos2 as $detailPos2) {
                        $barangId = $detailPos2['id'];

                        $detail = new PenjualanDetail;
                        $detail->barang_id = $barangId;
                        $detail->penjualan_id = $penjualan->id;
                        $detail->qty = $detailPos2['jumBarang'];
                        $detail->harga_jual = $detailPos2['hargaJual'];
                        $detail->harga_jual_rekomendasi = $detailPos2['RRP'];
                        $detail->save();
                    }
                    $this->redirect('index');
                }
            }
        }

        $this->render('import');
    }

    public function actionPrintStruk($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    $this->printLpr($id, $device, self::PRINT_STRUK);
                    break;
                /*
                  case Device::TIPE_PDF_PRINTER:
                  $this->exportPdf($id);
                  break;
                  case Device::TIPE_CSV_PRINTER:
                  $this->eksporCsv($id);
                  break;
                 */
                case Device::TIPE_TEXT_PRINTER:
                    $this->exportText($id, $device, self::PRINT_STRUK);
                    break;
            }
        }
    }

    public function actionPrintNota($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    $this->printLpr($id, $device, self::PRINT_NOTA);
                    break;
                /*
                  case Device::TIPE_PDF_PRINTER:
                  $this->exportPdf($id);
                  break;
                  case Device::TIPE_CSV_PRINTER:
                  $this->eksporCsv($id);
                  break;
                 */
                case Device::TIPE_TEXT_PRINTER:
                    $this->exportText($id, $device, self::PRINT_NOTA);
                    break;
            }
        }
    }

}
