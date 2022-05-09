<?php

class ReturpembelianController extends Controller
{
    const PROFIL_ALL      = 0;
    const PROFIL_SUPPLIER = Profil::TIPE_SUPPLIER;
    /* ============== */
    const PRINT_RETUR_PEMBELIAN = 0;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
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
        $returPembelianDetail = new ReturPembelianDetail('search');
        $returPembelianDetail->unsetAttributes();
        $returPembelianDetail->setAttribute('retur_pembelian_id', '=' . $id);

        $tipePrinterAvailable = [Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER, Device::TIPE_CSV_PRINTER];

        $printerReturPembelian = Device::model()->listDevices($tipePrinterAvailable);

        $kertasUntukPdf = ReturPembelian::model()->listNamaKertas();

        $this->render('view', [
            'model'                 => $this->loadModel($id),
            'returPembelianDetail'  => $returPembelianDetail,
            'printerReturPembelian' => $printerReturPembelian,
            'kertasUntukPdf'        => $kertasUntukPdf,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new ReturPembelian;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ReturPembelian'])) {
            $model->attributes = $_POST['ReturPembelian'];
            if ($model->save()) {
                $this->redirect(['ubah', 'id' => $model->id]);
            }
        }

        $supplierList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_SUPPLIER,
            'order'     => 'nama',
        ]);

        $this->render('tambah', [
            'model'        => $model,
            'supplierList' => $supplierList,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        /* Hanya ubah jika dan hanya jika statusnya masih DRAFT */
        if ($model->status != ReturPembelian::STATUS_DRAFT) {
            $this->redirect(['view', 'id' => $model->id]);
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['ReturPembelian'])) {
            $model->attributes = $_POST['ReturPembelian'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        /*
         * Untuk menampilkan dropdown barang sort by barcode;
         */
        /*
        $barcode = SupplierBarang::model()->ambilBarangBarcodePerSupplier($model->profil_id);
        $barangBarcode = array();
        foreach ($barcode as $barang) {
        $barangBarcode[$barang['id']] = "{$barang['barcode']} ({$barang['nama']})";
        }
         */
        /*
         * Untuk menampilkan dropdown barang sort by nama;
         */
        /*
        $nama = SupplierBarang::model()->ambilBarangNamaPerSupplier($model->profil_id);
        $barangNama = array();
        foreach ($nama as $barang) {
        $barangNama[$barang['id']] = "{$barang['nama']} ({$barang['barcode']})";
        }
         */
        $inventoryBalance = new InventoryBalance('search');
        $inventoryBalance->unsetAttributes();
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'inventory-balance-grid' && isset($_POST['barcode'])) {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $_POST['barcode']]);
            $inventoryBalance->setAttribute('barang_id', '=' . $barang->id);
            $inventoryBalance->setAttribute('qty', '<>0');
        } else {
            $inventoryBalance->setAttribute('barang_id', '=0');
        }

        $returPembelianDetail = new ReturPembelianDetail('search');
        $returPembelianDetail->unsetAttributes();
        $returPembelianDetail->setAttribute('retur_pembelian_id', '=' . $id);

        /* Ada scan dari aplikasi barcode scanner (android) */
        $scanBarcode = $_GET['barcodescan'] ?? null;

        $this->render('ubah', [
            'model'                => $model,
            //'barangBarcode' => $barangBarcode,
            //'barangNama' => $barangNama,
            'inventoryBalance'     => $inventoryBalance,
            'returPembelianDetail' => $returPembelianDetail,
            'scanBarcode'          => $scanBarcode,
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
        if ($model->status == ReturPembelian::STATUS_DRAFT) {
            ReturPembelianDetail::model()->deleteAll('retur_pembelian_id=:id', [':id' => $id]);
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
        $model = new ReturPembelian('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['ReturPembelian'])) {
            $model->attributes = $_GET['ReturPembelian'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return ReturPembelian the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = ReturPembelian::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param ReturPembelian $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'retur-pembelian-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

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

    public function renderLinkToUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
                $this->createUrl('ubah', ['id' => $data->id]) . '">' .
                $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

    public function renderLinkToSupplier($data)
    {
        return '<a href="' .
            $this->createUrl('supplier/view', ['id' => $data->profil_id]) . '">' .
            $data->profil->nama . '</a>';
    }

    public function renderRadioButton($data, $row)
    {
        return CHtml::radioButton('invid', $row == 0, ['value' => $data->id]);
    }

    /*
     * Mengembalikan barcode, nama, beserta stok barang
     */

    public function actionGetBarangInfo($barcode)
    {
        if (isset($barcode)) {
            $barang = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);
            $stock  = InventoryBalance::model()->find(['select' => 'sum(qty) jumlah', 'condition' => 'barang_id=:barangId', 'params' => [':barangId' => $barang->id]]);
            echo "<small>$barang->barcode</small> $barang->nama  <small>Stok</small> $stock->jumlah";
        }
    }

    /**
     * Cari Barang untuk autocomplete.
     * @param int $profilId Profil ID
     * @param text $term Text yang akan di cari
     */
    public function actionCariBarang($profilId, $term)
    {
        $q       = new CDbCriteria();
        $q->join = 'JOIN supplier_barang sp ON sp.barang_id = t.id';
        $q->addCondition('concat(barcode, nama) like :term');
        $q->addCondition('sp.supplier_id=:profilId');
        $q->order  = 'nama';
        $q->params = [':term' => "%{$term}%", ':profilId' => $profilId];
        $barangs   = Barang::model()->aktif()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label' => $barang->nama,
                'value' => $barang->barcode,
            ];
        }

        $this->renderJSON($r);
    }

    public function actionPilihInv($id)
    {
        $inventoryBalanceId = $_POST['invid'];
        $returQty           = $_POST['retur-qty'];

        //$model = $this->loadModel($id);

        $detail                       = new ReturPembelianDetail;
        $detail->retur_pembelian_id   = $id;
        $detail->inventory_balance_id = $inventoryBalanceId;
        $detail->qty                  = $returQty;
        $detail->save();
    }

    /*
     * Update qty retur pembelian detail, via ajax
     */

    public function actionUpdateQty()
    {
        $return = ['sukses' => false];
        if (isset($_POST['pk'])) {
            $pk    = $_POST['pk'];
            $value = $_POST['value'];
            if ($value > 0) {
                $returPembelianDetail = ReturPembelianDetail::model()->findByPk($pk);

                $returPembelianDetail->qty = $value;
                if ($returPembelianDetail->save()) {
                    $return = ['sukses' => true];
                }
            }
        }
        $this->renderJSON($return);
    }

    /*
     * Mengembalikan nilai total retur pembelian
     */

    public function actionTotal($id)
    {
        $returPembelian = $this->loadModel($id);
        $total          = $returPembelian->getTotal();
        echo $total;
    }

    /*
     * Hapus Retur Pembelian Detail, via ajax
     */

    public function actionHapusDetail($id)
    {
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

    public function actionSimpan($id)
    {
        $return = ['sukses' => false];
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $returPembelian = $this->loadModel($id);
            if ($returPembelian->status == ReturPembelian::STATUS_DRAFT) {
                /*
                 * simpan retur pembelian jika hanya dan hanya jika status masih draft
                 */
                $return = $returPembelian->simpanReturPembelian();
            }
        }
        $this->renderJSON($return);
    }

    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        $condition  = $tipe == Profil::TIPE_SUPPLIER ? 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_SUPPLIER : 'id>' . Profil::AWAL_ID;
        $profilList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => $condition,
            'order'     => 'nama',
        ]);
        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string .= '<option value="' . $profil->id . '">';
            $string .= $profil->nama . '</option>';
        }
        echo $string;
    }

    public function getNamaFile($nomor, $print)
    {
        switch ($print) {
            case self::PRINT_RETUR_PEMBELIAN:
                return "retur_pembelian-{$nomor}";
        }
    }

    public function getText($model, $print)
    {
        switch ($print) {
            case self::PRINT_RETUR_PEMBELIAN:
                return $model->returPembelianText();
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

    public function exportPdf($id, $kertas = ReturPembelian::KERTAS_A4, $draft = false)
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
         * Data Supplier
         */
        $profil = Profil::model()->findByPk($modelHeader->profil_id);

        /*
         * Retur Pembelian Detail
         */
        $returPembelianDetail = ReturPembelianDetail::model()->with('inventoryBalance', 'inventoryBalance.barang')->findAll([
            'condition' => "retur_pembelian_id={$id}",
            'order'     => 'barang.nama',
        ]);

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $listNamaKertas = ReturPembelian::listNamaKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $viewCetak      = '_pdf';
        if ($draft) {
            $viewCetak = '_pdf_draft';
        }
        $mpdf->WriteHTML($this->renderPartial(
            $viewCetak,
            [
                'modelHeader'          => $modelHeader,
                'branchConfig'         => $branchConfig,
                'profil'               => $profil,
                'returPembelianDetail' => $returPembelianDetail,
            ],
            true
        ));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumSuffix = ' / ';
        // $mpdf->pagenumPrefix = 'Halaman ';
        // Render PDF
        $mpdf->Output("{$modelHeader->nomor}.pdf", 'I');
    }

    public function exportText($id, $device, $print = 0)
    {
        $model    = $this->loadModel($id);
        $namaFile = $this->getNamaFile($model->nomor, $print);
        header('Content-type: text/plain');
        header("Content-Disposition: attachment; filename=\"{$namaFile}.text\"");
        header('Pragma: no-cache');
        header('Expire: 0');
        $text = $this->getText($model, $print);

        echo $device->revisiText($text);

        Yii::app()->end();
    }

    public function exportCsv($id, $device)
    {
        $model = $this->loadModel($id);
        $csv   = $model->keCsv();

        $timeStamp = date('Ymd His');
        $namaFile  = "{$model->nomor}_{$model->profil->nama}_{$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    public function actionPrintReturPembelian($id)
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
                case Device::TIPE_CSV_PRINTER:
                    $this->exportCsv($id, $device);
                    break;
            }
        }
    }

    /**
     * Menerbitkan piutang untuk retur_pembelian ID
     */
    public function actionPiutang($id)
    {
        if (!empty($id)) {
            $model  = $this->loadModel($id);
            $result = $model->terbitkanPiutang();

            if ($result['sukses']) {
                $this->redirect(['returpembelian/view', 'id' => $id]);
            } else {
                throw new CHttpException(500, 'Gagal terbit piutang');
            }
        }
    }

    /**
     * Mengubah status menjadi batal, dan menambah stok
     */
    public function actionBatal($id)
    {
        $return = ['sukses' => false];
        if (isset($_POST['batal']) && $_POST['batal']) {
            $returBeli = $this->loadModel($id);
            if ($returBeli->status == ReturPembelian::STATUS_POSTED) {
                /*
                 * bisa batal jika hanya dan hanya jika status posted
                 */
                $return = $returBeli->batal();
            }
        }
        $this->renderJSON($return);
    }

    public function renderBarang($data, $row)
    {
        // $pembelian    = $data->inventoryBalance->pembelianDetail == null ? '' : $data->inventoryBalance->pembelianDetail->pembelian->nomor;
        // $tglPembelian = $data->inventoryBalance->pembelianDetail == null ? '' : $data->inventoryBalance->pembelianDetail->pembelian->tanggal;
        // $ref          = $data->inventoryBalance->pembelianDetail == null ? "" : $data->inventoryBalance->pembelianDetail->pembelian->referensi;
        // $tglRef       = $data->inventoryBalance->pembelianDetail == null ? "" : $data->inventoryBalance->pembelianDetail->pembelian->tanggal_referensi;

        $text = '<strong>' . $data->inventoryBalance->barang->nama . '</strong><br />' . $data->inventoryBalance->barang->barcode .
            '<br />' .
            'Stok: ' . $data->inventoryBalance->barang->stok .
            '<br />' .
            number_format($data->inventoryBalance->harga_beli, 0, ',', '.') .
            ' x ' . $data->qty . ' ' . $data->inventoryBalance->barang->satuan->nama . ' = ' . $data->subTotal;
        // '<br />' .
        // 'Pembelian: ' . $pembelian . ' ' . $tglPembelian .
        // '<br />' .
        // 'Ref: ' . $ref . ' ' . $tglRef;

        return $text;
    }
}
