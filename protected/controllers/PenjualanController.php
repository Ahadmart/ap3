<?php

class PenjualanController extends Controller
{
    const PROFIL_ALL      = 0;
    const PROFIL_CUSTOMER = Profil::TIPE_CUSTOMER;
    /* ============== */
    const PRINT_INVOICE       = 0;
    const PRINT_STRUK         = 1;
    const PRINT_NOTA          = 2;
    const PRINT_INVOICE_DRAFT = 3;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl',     // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'deny', // deny guest
                'users' => ['guest'],
            ],
        ];
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

        $tipePrinterInvoiceRrp = [Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER];
        $tipePrinterStruk      = [Device::TIPE_LPR, Device::TIPE_TEXT_PRINTER, Device::TIPE_BROWSER_PRINTER];
        $tipePrinterNota       = [Device::TIPE_LPR, Device::TIPE_TEXT_PRINTER];

        $printerInvoiceRrp = Device::model()->listDevices($tipePrinterInvoiceRrp);
        $printerStruk      = Device::model()->listDevices($tipePrinterStruk);
        $printerNota       = Device::model()->listDevices($tipePrinterNota);

        $this->render('view', [
            'model'             => $this->loadModel($id),
            'penjualanDetail'   => $penjualanDetail,
            'printerInvoiceRrp' => $printerInvoiceRrp,
            'printerStruk'      => $printerStruk,
            'printerNota'       => $printerNota,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new Penjualan;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Penjualan'])) {
            $model->attributes = $_POST['Penjualan'];
            if ($model->save()) {
                $this->redirect(['ubah', 'id' => $model->id, 'uid' => Yii::app()->user->id]);
            }
        }

        $customerList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER,
            'order'     => 'nama'
        ]);

        $this->render('tambah', [
            'model'        => $model,
            'customerList' => $customerList,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     * @param integer $uid User ID of the model to be updated
     */
    public function actionUbah($id, $uid)
    {
        $model = $this->loadModel($id);

        // Jika status sudah tidak draft, tidak bisa ubah
        if ($model->status != Penjualan::STATUS_DRAFT) {
            $this->redirect(['view', 'id' => $id]);
        }

        /* Jika user ID yang di link tidak sama dengan yang di database, maka
         * redirect ke index (berarti link dimanipulasi)
         */
        if ($uid != $model->updated_by) {
            $this->redirect(['index']);
        }

        $model->scenario = 'tampil';

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Penjualan'])) {
            $model->attributes = $_POST['Penjualan'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        $penjualanDetail = new PenjualanDetail('search');
        $penjualanDetail->unsetAttributes();
        $penjualanDetail->setAttribute('penjualan_id', '=' . $id);

        $barang = new Barang('search');
        $barang->unsetAttributes();

        if (isset($_GET['cariBarang'])) {
            $barang->setAttribute('nama', $_GET['namaBarang']);
            $barang->setAttribute('status', Barang::STATUS_AKTIF);
        }

        $tipePrinterInvoiceRrp = [Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER];
        $printerInvoiceRrp     = Device::model()->listDevices($tipePrinterInvoiceRrp);

        $this->render('ubah', [
            'model'             => $model,
            'penjualanDetail'   => $penjualanDetail,
            'barang'            => $barang,
            'printerInvoiceRrp' => $printerInvoiceRrp,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == Penjualan::STATUS_DRAFT) {
            PenjualanDiskon::model()->deleteAll('penjualan_id=:penjualanId', ['penjualanId' => $id]);
            PenjualanMultiHarga::model()->deleteAll('penjualan_id=:penjualanId', ['penjualanId' => $id]);
            PenjualanDetail::model()->deleteAll('penjualan_id=:id', [':id' => $id]);
            PenjualanMemberOnline::model()->deleteAll('penjualan_id=:id', [':id' => $id]);
            $model->delete();
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Penjualan('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Penjualan'])) {
            $model->attributes = $_GET['Penjualan'];
        }
        $model->hideOpenTxn();

        $this->render('index', [
            'model' => $model,
        ]);
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
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

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
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['tambah_barang']) && $_POST['tambah_barang']) {
            $penjualan = $this->loadModel($id);
            $qty       = $_POST['qty'];
            $barcode   = $_POST['barcode'];
            $return    = $penjualan->transfer_mode ? $penjualan->transferBarang($barcode, $qty, true) : $penjualan->tambahBarang($barcode, $qty, true);
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
                $this->createUrl('view', ['id' => $data->id]) . '">' .
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
                $this->createUrl('ubah', ['id' => $data->id, 'uid' => $data->updated_by]) . '">' .
                $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

    public function actionSimpanPenjualan($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $penjualan = $this->loadModel($id);
            if ($penjualan->status == Penjualan::STATUS_DRAFT) {
                $return = $penjualan->simpan();
            }
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
        $penjualan        = $this->loadModel($id);
        $total            = $penjualan->ambilTotal();
        $totalF           = $penjualan->total;
        $return['sukses'] = true;
        $return['total']  = $total;
        $return['totalF'] = $totalF;
        $this->renderJSON($return);
    }

    /**
     * Ambil poin penjualan saat ini
     * @param int $penjualanId
     * @return json
     */
    public function actionPoin($id)
    {
        $penjualan = $this->loadModel($id);
        $curPoin   = $penjualan->getCurPoin();
    }

    public function formatHargaJual($data)
    {
        return number_format($data->harga_jual, 0, ',', '.');
    }

    public function formatHargaJualRekomendasi($data)
    {
        return number_format($data->harga_jual_rekomendasi, 0, ',', '.');
    }

    public function tampilkanHargaBeli($data)
    {
        $hpp          = HargaPokokPenjualan::model()->findAll('penjualan_detail_id=' . $data->id);
        $barisPertama = true;
        $text         = '';
        foreach ($hpp as $hargaBeli) {
            if (!$barisPertama) {
                $text .= '<br />';
            }
            $text .= number_format($hargaBeli->harga_beli, 0, ',', '.') . ' x ' . $hargaBeli->qty;
            $barisPertama = false;
        }
        return $text;
    }

    /**
     * Render Faktur/Invoice dalam format PDF
     * @param int $id penjualan ID
     */
    public function exportPdf($id, $draft = false)
    {
        $modelHeader = $this->loadModel($id);
        $configs     = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
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
        $penjualanDetail = PenjualanDetail::model()->with('barang')->findAll([
            'condition' => "penjualan_id={$id}",
            'order'     => 'barang.nama',
        ]);

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4', 'tempDir' => __DIR__ . '/../runtime/']);

        $viewInvoice = '_invoice';
        if ($draft) {
            $viewInvoice = '_invoice_draft';
        }
        $mpdf->WriteHTML($this->renderPartial(
            $viewInvoice,
            [
                'modelHeader'     => $modelHeader,
                'branchConfig'    => $branchConfig,
                'customer'        => $customer,
                'penjualanDetail' => $penjualanDetail,
            ],
            true
        ));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumSuffix = ' / ';
        // $mpdf->pagenumPrefix = 'Halaman ';
        // Render PDF
        $mpdf->Output("{$modelHeader->nomor}.pdf", 'I');
    }

    /**
     * Render csv untuk didownload
     * @param int $id penjualan ID
     */
    public function actionExportCsv($id)
    {
        $model = $this->loadModel($id);

        $strukturHarusAda = true; // fix me: tambahkan di config dan load dari config
        // Throw exception jika ada barang tanpa struktur
        if ($strukturHarusAda) {
            $tanpaStruktur = $model->ambilDetailTanpaStruktur();
            if (!empty($tanpaStruktur)) {
                $t = '';
                foreach ($tanpaStruktur as $detail) {
                    $t .= ' | ' . $detail->barang->nama . '(' . $detail->barang->barcode . ') | ';
                }
                throw new CHttpException(500, 'Barang berikut ini belum ada strukturnya, lengkapi terlebih dahulu: ' . $t); //print_r($tanpaStruktur, true));
            }
        }

        $csv   = $model->eksporCsv();

        $timeStamp = date("Y-m-d--H-i");
        $namaFile  = "{$model->nomor}-{$model->profil->nama}-{$timeStamp}";

        $hash = hash_hmac('sha256', $csv, $namaFile, false);

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile . '-' . $hash,
            'csv'      => $csv,
        ]);
    }

    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        $condition  = $tipe == Profil::TIPE_CUSTOMER ? 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER : 'id>' . Profil::AWAL_ID;
        $profilList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => $condition,
            'order'     => 'nama'
        ]);
        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string .= '<option value="' . $profil->id . '">';
            $string .= $profil->nama . '</option>';
        }
        echo $string;
    }

    public function exportText($id, $device, $print = 0)
    {
        $model    = $this->loadModel($id);
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
            case self::PRINT_INVOICE_DRAFT:
                return "invoice-draft";
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
            case self::PRINT_INVOICE_DRAFT:
                $draft = true;
                return $model->invoiceText($draft);
        }
    }

    public function printLpr($id, $device, $print = 0)
    {
        $model = $this->loadModel($id);
        $text  = $this->getText($model, $print);
        $device->printLpr($text);
        $this->renderPartial('_print_autoclose', [
            'text' => $text,
        ]);
    }

    public function printBrowser($id, $device, $print = 0)
    {
        $model = $this->loadModel($id);
        $text  = $this->getText($model, $print);
        $this->renderPartial('_print_autoclose_browser', [
            'text' => $text,
        ]);
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

    public function actionPrintDraftInvoice($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    $this->printLpr($id, $device, self::PRINT_INVOICE_DRAFT);
                    break;
                case Device::TIPE_PDF_PRINTER:
                    $this->exportPdf($id, self::PRINT_INVOICE_DRAFT);
                    break;
                case Device::TIPE_TEXT_PRINTER:
                    $this->exportText($id, $device, self::PRINT_INVOICE_DRAFT);
                    break;
            }
        }
    }

    public function actionImport()
    {
        if (isset($_POST['nomor'])) {
            $dbAhadPos2    = $_POST['database'];
            $nomor         = $_POST['nomor'];
            $penjualanPos2 = Yii::app()->db->createCommand("
                     SELECT t.tglTransaksiJual, c.namaCustomer
                     FROM {$dbAhadPos2}.transaksijual t
                     JOIN {$dbAhadPos2}.customer c on t.idCustomer=c.idCustomer
                     WHERE idTransaksiJual = :nomor")
                ->bindValue(':nomor', $nomor)
                ->queryRow();
            $profil = Profil::model()->find('nama=:nama', ['nama' => trim($penjualanPos2['namaCustomer'])]);
            if (!is_null($profil)) {
                $penjualan            = new Penjualan;
                $penjualan->profil_id = $profil->id;
                if ($penjualan->save()) {
                    $penjualanDetailPos2 = Yii::app()->db
                        ->createCommand("
                           select d.barcode, d.jumBarang, d.hargaBeli, d.hargaJual, d.RRP, barang.id
                           from {$dbAhadPos2}.detail_jual d
                           join barang on d.barcode=barang.barcode
                           where d.nomorStruk = :nomor
                               ")
                        ->bindValue(':nomor', $nomor)
                        ->queryAll();

                    foreach ($penjualanDetailPos2 as $detailPos2) {
                        $barangId = $detailPos2['id'];

                        $detail                         = new PenjualanDetail;
                        $detail->barang_id              = $barangId;
                        $detail->penjualan_id           = $penjualan->id;
                        $detail->qty                    = $detailPos2['jumBarang'];
                        $detail->harga_jual             = $detailPos2['hargaJual'];
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
                case Device::TIPE_BROWSER_PRINTER:
                    $this->printBrowser($id, $device, self::PRINT_STRUK);
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
