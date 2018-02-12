<?php

class PoController extends Controller
{
    const PROFIL_ALL      = 0;
    const PROFIL_SUPPLIER = Profil::TIPE_SUPPLIER;
    /* ============== */

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

        $poDetail = new PoDetail('search');
        $poDetail->unsetAttributes();
        $poDetail->setAttribute('po_id', '=' . $id);
        if (isset($_GET['PoDetail'])) {
            $poDetail->attributes = $_GET['PoDetail'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER, Device::TIPE_PDF_PRINTER];

        $printerPo = empty($tipePrinterAvailable) ? [] : Device::model()->listDevices($tipePrinterAvailable);

        $kertasUntukPdf = Po::listNamaKertas();

        $this->render('view', [
            'model'          => $model,
            'poDetail'       => $poDetail,
            'printerPo'      => $printerPo,
            'kertasUntukPdf' => $kertasUntukPdf
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new Po;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Po'])) {
            $model->attributes = $_POST['Po'];
            if ($model->save()) {
                $this->redirect(['ubah', 'id' => $model->id]);
            }
        }

        $supplierList = Profil::model()->profilTrx()->tipeSupplier()->orderByNama()->findAll([
            'select' => 'id, nama',
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        // Jika PO sudah disimpan (status bukan draft) maka tidak bisa diubah lagi
        if ($model->status != Po::STATUS_DRAFT) {
            $this->redirect(['view', 'id' => $id]);
        }

        if (isset($_POST['Po'])) {
            $model->attributes = $_POST['Po'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        /*
         * Untuk menampilkan dropdown barang sort by barcode;
         */
        $barcode       = SupplierBarang::model()->ambilBarangBarcodePerSupplier($model->profil_id);
        $barangBarcode = [];
        foreach ($barcode as $barang) {
            $barangBarcode[$barang['id']] = "{$barang['barcode']} ({$barang['nama']})";
        }

        /*
         * Untuk menampilkan dropdown barang sort by nama;
         */
        $nama       = SupplierBarang::model()->ambilBarangNamaPerSupplier($model->profil_id);
        $barangNama = [];
        foreach ($nama as $barang) {
            $barangNama[$barang['id']] = "{$barang['nama']} ({$barang['barcode']})";
        }

        // Untuk menampilkan daftar barang, pada pencarian tabel
        $barangList = new Barang('search');
        $barangList->unsetAttributes();
        $curSupplierCr = null;

        if (isset($_GET['cariBarang'])) {
            $barangList->setAttribute('nama', $_GET['namaBarang']);
            $curSupplierCr        = new CDbCriteria;
            $curSupplierCr->join  = "JOIN supplier_barang ON barang_id = t.id AND supplier_id = {$model->profil_id}";
            $curSupplierCr->order = 'nama ASC';
        }

        $PODetail = new PoDetail('search');
        $PODetail->unsetAttributes();
        $PODetail->setAttribute('po_id', '=' . $id);
        if (isset($_GET['PoDetail'])) {
            $PODetail->attributes = $_GET['PoDetail'];
        }

        /* Model untuk membuat barang baru */
        $barang = new Barang;

        // Kondisi untuk menampilkan pilih barang
        $pilihBarang = true;

        // Tipe cari barang
        $configCariBarang = Config::model()->find("nama='po.caribarangmode'");

        $this->render('ubah', [
            'model'         => $model,
            'barangBarcode' => $barangBarcode,
            'barangNama'    => $barangNama,
            'PODetail'      => $PODetail,
            'barangList'    => $barangList,
            'curSupplierCr' => $curSupplierCr,
            'barang'        => $barang,
            'pilihBarang'   => $pilihBarang,
            'tipeCari'      => $configCariBarang->nilai,
        ]);
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
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Po('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Po'])) {
            $model->attributes = $_GET['Po'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Po the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Po::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Po $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'po-form') {
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

    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        if ($tipe == Profil::TIPE_SUPPLIER) {
            $profilList = Profil::model()->profilTrx()->tipeSupplier()->orderByNama()->findAll([
                'select' => 'id, nama',
            ]);
        } else {
            $profilList = Profil::model()->profilTrx()->orderByNama()->findAll([
                'select' => 'id, nama',
            ]);
        }

        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string .= '<option value="' . $profil->id . '">';
            $string .= $profil->nama . '</option>';
        }
        echo $string;
    }

    /**
     * Untuk mengambil informasi barang untuk ditampilkan
     * pada saat input po
     */
    public function actionGetBarang()
    {
        if (isset($_POST['barangId'])) {
            $barangId = $_POST['barangId'];
        } elseif (isset($_POST['barcode'])) {
            $barang   = Barang::model()->find('barcode = :barcode', [':barcode' => $_POST['barcode']]);
            $barangId = $barang->id;
        }
        $barang = Pembelian::model()->ambilDataBarang($barangId);
        $arr    = [
            'barangId'       => $barangId,
            'nama'           => $barang['nama'],
            'barcode'        => $barang['barcode'],
            'hargaBeli'      => number_format($barang['harga_beli'], 0, '', ''),
            'labelHargaBeli' => number_format($barang['harga_beli'], 0, ',', '.'),
            'hargaJual'      => number_format($barang['harga_jual'], 0, '', ''),
            'labelHargaJual' => number_format($barang['harga_jual'], 0, ',', '.'),
            'satuan'         => $barang['satuan'],
        ];
        echo CJSON::encode($arr);
    }

    public function actionTambahBarangBaru($id)
    {
        $return = [
            'sukses' => false,
        ];
        $model = $this->loadModel($id);
        if (isset($_POST['Barang'])) {
            $barang             = new Barang;
            $barang->attributes = $_POST['Barang'];
            if ($barang->save()) {
                $supplierBarang              = new SupplierBarang;
                $supplierBarang->supplier_id = $model->profil_id;
                $supplierBarang->barang_id   = $barang->id;
                if ($supplierBarang->save()) {
                    $return = [
                        'sukses'   => true,
                        'barangId' => $barang->id,
                        'barcode'  => $barang->barcode,
                        'nama'     => $barang->nama,
                        'satuan'   => $barang->satuan->nama,
                    ];
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

    public function actionTambahBarang($id)
    {
        // Jika ada post input-detail, berarti ada input-an barang
        if (isset($_POST['input-detail']) && $_POST['input-detail'] == 1) {
            $barang = Barang::model()->findByPk($_POST['barang-id']);

            $detail                      = new PoDetail;
            $detail->po_id               = $id;
            $detail->barang_id           = $_POST['barang-id'];
            $detail->barcode             = $barang->barcode;
            $detail->nama                = $barang->nama;
            $detail->qty_order           = $_POST['qty'] > 0 ? $_POST['qty'] : 0;
            $detail->harga_beli          = $_POST['hargabeli'];
            $detail->harga_jual          = $_POST['hargajual'];

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
     * Update qty detail po via ajax
     */
    public function actionUpdateQty()
    {
        if (isset($_POST['pk'])) {
            $pk                = $_POST['pk'];
            $qty               = $_POST['value'];
            $detail            = PoDetail::model()->findByPk($pk);
            $detail->qty_order = $qty;

            $return = ['sukses' => false];
            if ($detail->save()) {
                $return = ['sukses' => true];
            }

            $this->renderJSON($return);
        }
    }

    /**
     * Nilai total PO dalam text terformat ribuan
     * @param int $id
     */
    public function actionTotal($id)
    {
        $po              = $this->loadModel($id);
        $total           = [];
        $total['sukses'] = true;
        $total['totalF'] = $po->total;

        $this->renderJSON($total);
    }

    /**
     * Hapus detail po
     * @param integer $id the ID of the detail to be deleted
     */
    public function actionHapusDetail($id)
    {
        $detail = PoDetail::model()->findByPk($id);
        $detail->delete();
    }

    /**
     * Simpan po:
     * Update status dari draft menjadi po, dapat nomor
     * @param int $id
     */
    public function actionSimpan($id)
    {
        $return = ['sukses' => false];
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $po = $this->loadModel($id);
            if ($po->status == Po::STATUS_DRAFT) {
                /*
                 * simpan pembelian jika hanya dan hanya jika status masih draft
                 */
                $return = $po->simpan();
            }
        }
        $this->renderJSON($return);
    }

    public function actionPrint($id)
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_LPR:
                    // $this->printLpr($id, $device);
                    break;
                case Device::TIPE_PDF_PRINTER:
                    $this->exportPdf($id, $_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->eksporCsv($id);
                    break;
            }
        }
    }

    public function exportPdf($id, $kertas = Po::KERTAS_A4, $draft = false)
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
         * Po Detail
         */
        $poDetail = PoDetail::model()->findAll([
            'condition' => "po_id={$id}",
            'order'     => 'nama'
        ]);

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendors/autoload.php';

        $listNamaKertas = Po::listNamaKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);

        $viewCetak      = '_pdf';
        if ($draft) {
            $viewCetak = '_pdf_draft';
        }
        $mpdf->WriteHTML($this->renderPartial(
            $viewCetak,
            [
                    'modelHeader'  => $modelHeader,
                    'branchConfig' => $branchConfig,
                    'profil'       => $profil,
                    'poDetail'     => $poDetail
                        ],
            true
        ));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumSuffix = ' / ';
        // $mpdf->pagenumPrefix = 'Hlm ';
        // Render PDF
        $mpdf->Output("PO {$modelHeader->nomor}.pdf", 'I');
    }

    /**
     * Render csv untuk didownload
     * @param int $id PO Id
     */
    public function eksporCsv($id)
    {
        $model = $this->loadModel($id);
        $text = $model->toCsv();

        $timeStamp = date("Y-m-d--H-i");
        $namaFile = "PO-{$model->nomor}-{$model->profil->nama}-{$timeStamp}.csv";
        $contentTypeMeta = 'text/csv';

        $this->renderPartial('_file_text', [
            'namaFile' => $namaFile,
            'text' => $text,
            'contentType' => $contentTypeMeta
        ]);
    }

    public function actionBeli($id)
    {
        if (isset($id)) {
            $model  = $this->loadModel($id);
            $return = $model->beli();
            if ($return['sukses']) {
                $this->redirect(['pembelian/ubah', 'id' => $return['data']['pembelianId']]);
            }
        }
    }
}
