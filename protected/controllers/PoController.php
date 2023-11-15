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
            'kertasUntukPdf' => $kertasUntukPdf,
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

        /*  Mode untuk input item barang,
        bisa manual, atau bisa lewat analisa PLS (Potensi Lost Sales) terlebih dahulu
         */
        $modeManual = true;
        if (isset($_GET['modepls']) && $_GET['modepls']) {
            $modeManual = false;
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
        $barangList->aktif();
        $barangList->setAttribute('nama', '=" "'); // Init data: agar barang tidak tampil
        $curSupplierCr      = null;
        $configFilterPerSup = Config::model()->find('nama=:filterPerSupplier', [':filterPerSupplier' => 'po.filterpersupplier']);

        if (isset($_GET['cariBarang'])) {
            $barangList->setAttribute('nama', $_GET['namaBarang']);
            if (isset($configFilterPerSup) && $configFilterPerSup->nilai == 1) {
                $curSupplierCr        = new CDbCriteria;
                $curSupplierCr->join  = "JOIN supplier_barang ON barang_id = t.id AND supplier_id = {$model->profil_id}";
                $curSupplierCr->order = 'nama ASC';
            }
        }

        $PODetail = new PoDetail('search');
        $PODetail->unsetAttributes();
        $PODetail->setAttribute('po_id', '=' . $id);
        $PODetail->status = PoDetail::STATUS_ORDER;
        if (isset($_GET['PoDetail'])) {
            $PODetail->attributes = $_GET['PoDetail'];
        }

        /* Model untuk membuat barang baru */
        $barang = new Barang;

        // Kondisi untuk menampilkan pilih barang
        $pilihBarang = $modeManual == true;

        // Tipe cari barang
        $configCariBarang = Config::model()->find("nama='po.caribarangmode'");

        $modelReportPls = new ReportPlsForm;

        $PLSDetail = new PoDetail('search');
        $PLSDetail->unsetAttributes();
        $PLSDetail->setAttribute('po_id', '=' . $id);
        $PLSDetail->status = PoDetail::STATUS_DRAFT;
        if (isset($_GET['PoDetail'])) {
            $PLSDetail->attributes = $_GET['PoDetail'];
        }

        $pageSize = 20;
        if (isset($_GET['pageSize'])) {
            $pageSize = (int) $_GET['pageSize'];
        }

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = $_GET['barcodescan'];
        }

        $this->render('ubah', [
            'model'          => $model,
            'modeManual'     => $modeManual,
            'barangBarcode'  => $barangBarcode,
            'barangNama'     => $barangNama,
            'PODetail'       => $PODetail,
            'barangList'     => $barangList,
            'curSupplierCr'  => $curSupplierCr,
            'barang'         => $barang,
            'pilihBarang'    => $pilihBarang,
            'tipeCari'       => $configCariBarang->nilai,
            'modelReportPls' => $modelReportPls,
            'plsDetail'      => $PLSDetail,
            'pageSize'       => $pageSize,
            'scanBarcode'    => $scanBarcode,
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
        if ($model->status == Po::STATUS_DRAFT) {
            PoDetail::model()->deleteAll('po_id=:poId', [':poId' => $id]);
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
     * @param  integer        $id the ID of the model to be loaded
     * @return Po             the loaded model
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
     * @param  obj    $data
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
     * @param  obj    $data
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
    public function actionGetBarang($id)
    {
        if (isset($_POST['barangId'])) {
            $barangId = $_POST['barangId'];
            $barang   = Barang::model()->findByPk($barangId);
        } elseif (isset($_POST['barcode'])) {
            $barang   = Barang::model()->find('barcode = :barcode', [':barcode' => $_POST['barcode']]);
            $barangId = $barang->id;
        }

        $configFilterPerSup = Config::model()->find('nama=:filterPerSupplier', [
            ':filterPerSupplier' => 'po.filterpersupplier',
        ]);

        if (isset($configFilterPerSup) && $configFilterPerSup->nilai == 1) {
            $po          = Po::model()->findByPk($id);
            $cekSupplier = SupplierBarang::model()->find('barang_id=:barangId AND supplier_id=:supplierId', [
                ':barangId'   => $barangId,
                ':supplierId' => $po->profil_id,
            ]);

            if (!is_null($cekSupplier)) {
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
                $this->renderJSON([
                    'sukses' => true,
                    'info'   => $arr,
                ]);
            } elseif (is_null($cekSupplier)) {
                $this->renderJSON([
                    'sukses' => false,
                    'error'  => [
                        'code' => 500,
                        'msg'  => 'Barang "' . $barang->nama . '" (' . $barang->barcode . ') tidak ditemukan di profil ini',
                    ],
                ]);
            }
        } elseif ($configFilterPerSup->nilai == 0) {
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
            $this->renderJSON([
                'sukses' => true,
                'info'   => $arr,
            ]);
        }
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
        if (isset($_POST['input-detail']) && $_POST['input-detail'] == 1 && $_POST['qty'] > 0) {
            $sudahAda = PoDetail::sudahAda($id, $_POST['barang-id']);
            $qtyOrder = 0;
            // if (!is_null($sudahAda)) {
            //     $qtyOrder = $sudahAda->qty_order;
            //     $sudahAda->delete();
            // }
            if (!is_null($sudahAda)) {
                if (PoDetail::model()->updateByPk($sudahAda->id, ['qty_order' => $sudahAda->qty_order + $_POST['qty']]) > 0) {
                    echo 'Update berhasil';
                } else {
                    echo 'Update gagal';
                }
            } else {
                $barang = Barang::model()->findByPk($_POST['barang-id']);

                $detail             = new PoDetail;
                $detail->po_id      = $id;
                $detail->barang_id  = $_POST['barang-id'];
                $detail->barcode    = $barang->barcode;
                $detail->nama       = $barang->nama;
                $detail->qty_order  = $qtyOrder + $_POST['qty'];
                $detail->harga_beli = $_POST['hargabeli'];
                $detail->harga_jual = $_POST['hargajual'];
                $detail->status     = PoDetail::STATUS_ORDER;
                $detail->stok       = $barang->stok;
                // echo $id.' '.$_POST['barang-id'].' '.$_POST['qty'].' '.$_POST['tanggal_kadaluwarsa'].' '.$_POST['hargabeli'];
                // echo terlihat di console
                if ($detail->save()) {
                    //HargaJualBarang::model()->updateHargaJual($_POST['barang-id'], $inputHargaJual);
                    echo 'Tambah barang berhasil';
                } else {
                    echo 'Tambah barang gagal';
                }
            }
        }
    }

    /**
     * Update qty detail po via ajax
     */
    public function actionUpdateQty()
    {
        if (isset($_POST['pk'])) {
            $pk  = $_POST['pk'];
            $qty = $_POST['value'];

            if (PoDetail::model()->updateByPk($pk, ['qty_order' => $qty]) > 0) {
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
    // public function actionHapusDetail($id)
    // {
    //     $detail = PoDetail::model()->findByPk($id);
    //     $detail->delete();
    // }

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
            'order'     => 'nama',
        ]);

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';

        $listNamaKertas = Po::listNamaKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);

        $viewCetak = '_pdf';
        if ($draft) {
            $viewCetak = '_pdf_draft';
        }
        $mpdf->WriteHTML($this->renderPartial(
            $viewCetak,
            [
                'modelHeader'  => $modelHeader,
                'branchConfig' => $branchConfig,
                'profil'       => $profil,
                'poDetail'     => $poDetail,
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
        $text  = $model->toCsv();

        $timeStamp       = date('Ymd His');
        $namaFile        = "PO_{$model->nomor}_{$model->profil->nama}_{$timeStamp}.csv";
        $contentTypeMeta = 'text/csv';

        $this->renderPartial('_file_text', [
            'namaFile'    => $namaFile,
            'text'        => $text,
            'contentType' => $contentTypeMeta,
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

    public function actionAmbilPls($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'UNDER CONSTRUCTION! Belum Bisa dipakai',
            ],
        ];
        $model = $this->loadModel($id);

        $configFilterPerSup = Config::model()->find('nama=:filterPerSupplier', [
            ':filterPerSupplier' => 'po.filterpersupplier',
        ]);

        $profilId = null;
        if (isset($configFilterPerSup) && $configFilterPerSup->nilai == 1) {
            $profilId = $model->profil_id;
        }
        $rakId       = empty($_POST['rakId']) ? null : $_POST['rakId'];
        $strukLv1    = empty($_POST['strukLv1']) ? null : $_POST['strukLv1'];
        $strukLv2    = empty($_POST['strukLv2']) ? null : $_POST['strukLv2'];
        $strukLv3    = empty($_POST['strukLv3']) ? null : $_POST['strukLv3'];
        $leadTime    = empty($_POST['leadTime']) ? 0 : $_POST['leadTime'];
        $ssd         = empty($_POST['ssd']) ? 0 : $_POST['ssd'];
        $semuaBarang = $_POST['semuaBarang'] == 'true' ? true : false;

        $return = $model->analisaPLS($_POST['hariPenjualan'], $_POST['orderPeriod'], $leadTime, $ssd, $profilId, $rakId, $strukLv1, $strukLv2, $strukLv3, $semuaBarang);

        $attributes = [
            'po_id'        => $model->id,
            'range'        => $_POST['hariPenjualan'],
            'order_period' => $_POST['orderPeriod'],
            'lead_time'    => $leadTime,
            'ssd'          => $ssd,
            'rak_id'       => $rakId,
            'struktur_lv1' => $strukLv1,
            'struktur_lv2' => $strukLv2,
            'struktur_lv3' => $strukLv3
        ];
        $plsParam = PoAnalisaplsParam::model()->find('po_id=:poId', [':poId' => $model->id]);
        if (is_null($plsParam)) {
            $plsParam = new PoAnalisaplsParam();
            echo 'Analisa Baru';
            print_r($attributes);
        };
        $plsParam->attributes = $attributes;
        if (!$plsParam->save()) {
            echo 'Gagal simpan analisa';
            print_r($plsParam->errors);
        }

        // $return['rakId'] = $_POST['rakId'];
        $this->renderJSON($return);
        // print_r($return);
    }

    /**
     * List Barang untuk autocomplete
     * @param  int  $profilId
     * @param  text $term
     * @return JSON nama, barcode, dan id barang
     */
    public function actionCariBarang($profilId, $term)
    {
        $q = new CDbCriteria();
        $q->addCondition('(barcode like :term OR nama like :term) AND status=:status');
        $q->order  = 'nama';
        $q->params = [
            ':term'   => "%{$term}%",
            ':status' => Barang::STATUS_AKTIF,
        ];

        $configFilterPerSup = Config::model()->find('nama=:filterPerSupplier', [
            ':filterPerSupplier' => 'po.filterpersupplier',
        ]);

        if (isset($configFilterPerSup) && $configFilterPerSup->nilai == 1) {
            $q->join                = 'JOIN supplier_barang s ON t.id=s.barang_id AND s.supplier_id=:profilId';
            $q->params[':profilId'] = $profilId;
        }
        $barangs = Barang::model()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label' => $barang->nama,
                'value' => $barang->barcode,
                'id'    => $barang->id,
            ];
        }

        $this->renderJSON($r);
    }

    /**
     * Render html link (<a>) untuk edit qty order
     * @param  activeRecord $data
     * @param  type         $row
     * @return html         link editable
     */
    public function renderOrderEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="r"';
        }
        return '<a href="#" class="editable-order" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
            Yii::app()->controller->createUrl('inputorder') . '">' . $data->qty_order . '</a>';
    }

    public function renderTombolSetOrder($data, $row)
    {
        return CHtml::link('<i class="fa fa-plus-square"><i>', Yii::app()->controller->createUrl('setorder'), [
            'data-detailid' => $data->id,
            'class'         => 'tombol-setorder',
        ]);
    }

    public function actionSetOrder($id)
    {
        $return = ['sukses' => false];
        if (isset($_POST['detailId'])) {
            $pk          = $_POST['detailId'];
            $rowAffected = PoDetail::model()->updateByPk($pk, ['status' => PoDetail::STATUS_ORDER]);
            if ($rowAffected > 0) {
                $return = ['sukses' => true];
            }
        }
        $this->renderJSON($return);
    }

    public function actionUnsetOrder($id)
    {
        $return      = ['sukses' => false];
        $rowAffected = PoDetail::model()->updateByPk($id, ['status' => PoDetail::STATUS_DRAFT]);
        if ($rowAffected > 0) {
            $return = ['sukses' => true];
        }
        $this->renderJSON($return);
    }

    public function actionInputOrder()
    {
        $return = ['sukses' => false];
        if (isset($_POST['pk'])) {
            $pk          = $_POST['pk'];
            $rowAffected = PoDetail::model()->updateByPk($pk, [
                'qty_order' => $_POST['value'],
                'status'    => PoDetail::STATUS_ORDER,
            ]);
            if ($rowAffected > 0) {
                $return = ['sukses' => true];
            }
        }
        $this->renderJSON($return);
    }

    public function actionHapusDetail($id)
    {
        $return      = ['sukses' => false];
        $rowAffected = PoDetail::model()->deleteByPk($id);
        if ($rowAffected > 0) {
            $return = ['sukses' => true];
        }
        $this->renderJSON($return);
    }

    public function actionAmbilTotal($id)
    {
        $model = $this->loadModel($id);
        $total = $model->total;
        echo $total;
    }

    public function actionOrderSemua($id)
    {
        $return          = ['sukses' => false];
        $condition       = 'po_id=:POId ';
        $params[':POId'] = $id;
        if (!empty($_GET['barcode'])) {
            $condition .= 'AND barcode LIKE :barcode ';
            $params[':barcode'] = "%{$_GET['barcode']}%";
        }
        if (!empty($_GET['nama'])) {
            $condition .= 'AND nama LIKE :nama';
            $params[':nama'] = "%{$_GET['nama']}%";
        }
        $rowAffected = PoDetail::model()->updateAll(['status' => PoDetail::STATUS_ORDER], $condition, $params);
        if ($rowAffected > 0) {
            $return = ['sukses' => true];
        }
        $this->renderJSON($return);
    }

    public function actionUnOrderSemua($id)
    {
        $return      = ['sukses' => false];
        $rowAffected = PoDetail::model()->updateAll(['status' => PoDetail::STATUS_DRAFT], 'po_id=:POId', [':POId' => $id]);
        if ($rowAffected > 0) {
            $return = ['sukses' => true];
        }
        $this->renderJSON($return);
    }

    public function actionAmbilStrukturLv2()
    {
        $lv1Id   = $_GET['parent-id'];
        $listLv2 = StrukturBarang::listStrukLv2($lv1Id);
        $r       = '';
        foreach ($listLv2 as $key => $value) {
            $r .= '<option value="' . $key . '">' . $value . '</option>';
        }
        echo $r;
    }

    public function actionAmbilStrukturLv3()
    {
        $lv2Id   = $_GET['parent-id'];
        $listLv3 = StrukturBarang::listStrukLv3($lv2Id);
        $r       = '';
        foreach ($listLv3 as $key => $value) {
            $r .= '<option value="' . $key . '">' . $value . '</option>';
        }
        echo $r;
    }

    /**
     * Render html link (<a>) untuk edit restock_min (minimum restock)
     * @param  activeRecord $data
     * @param  type         $row
     * @return html         link editable
     */
    public function renderRestockMinEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="r"';
        }
        return '<a href="#" class="editable-order" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
            Yii::app()->controller->createUrl('editrestockmin') . '">' . $data->restock_min . '</a>';
    }

    public function actionEditRestockMin()
    {
        $return = ['sukses' => false];
        if (isset($_POST['pk'])) {
            $pk          = $_POST['pk'];
            $poDetail    = PoDetail::model()->findByPk($pk);
            $rowAffected = $poDetail->updateByPk($pk, [
                'restock_min' => $_POST['value'],
                // 'saran_order' => $poDetail->saran_order - $poDetail->restock_min + $_POST['value'],
                'qty_order'   => $poDetail->saran_order + $_POST['value'],
                'updated_by'  => Yii::app()->user->id,
            ]);
            // if ($_POST['value'] > $poDetail->qty_order) {
            //     $rowAffected = $poDetail->updateByPk($pk, [
            //         'restock_min' => $_POST['value'],
            //         'qty_order'   => $_POST['value'],
            //         'updated_by'  => Yii::app()->user->id,
            //     ]);
            // } else {
            //     $rowAffected = $poDetail->updateByPk($pk, [
            //         'restock_min' => $_POST['value'],
            //         'updated_by'  => Yii::app()->user->id,
            //     ]);
            // }
            if ($rowAffected > 0) {
                $return = ['sukses' => true];
            }
            // Barang juga diupdate mudah-mudahan lancar tanpa transaction
            $barangId = PoDetail::model()->findByPk($pk)->barang_id;
            Barang::model()->updateByPk($barangId, [
                'restock_min' => $_POST['value'],
                'updated_by'  => Yii::app()->user->id,
            ]);
        }
        $this->renderJSON($return);
    }
}
