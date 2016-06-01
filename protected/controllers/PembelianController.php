<?php

class PembelianController extends Controller
{

    const PROFIL_ALL = 0;
    const PROFIL_SUPPLIER = Profil::TIPE_SUPPLIER;
    /* ============== */
    const PRINT_PEMBELIAN = 0;

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
        $pembelianDetail = new PembelianDetail('search');
        $pembelianDetail->unsetAttributes();
        $pembelianDetail->setAttribute('pembelian_id', '=' . $id);
        if (isset($_GET['PembelianDetail'])) {
            $pembelianDetail->attributes = $_GET['PembelianDetail'];
        }

        $tipePrinterAvailable = array(Device::TIPE_LPR, Device::TIPE_PDF_PRINTER, Device::TIPE_TEXT_PRINTER);

        $printerPembelian = Device::model()->listDevices($tipePrinterAvailable);

        $kertasUntukPdf = Pembelian::model()->listNamaKertas();

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'pembelianDetail' => $pembelianDetail,
            'printerPembelian' => $printerPembelian,
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
        $model = new Pembelian;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pembelian'])) {
            $model->attributes = $_POST['Pembelian'];
            if ($model->save())
                $this->redirect(array('ubah', 'id' => $model->id));
        }

        $supplierList = Profil::model()->profilTrx()->tipeSupplier()->findAll(array(
            'select' => 'id, nama'
        ));

        $this->render('tambah', array(
            'model' => $model,
            'supplierList' => $supplierList
        ));
    }

    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        if ($tipe == Profil::TIPE_SUPPLIER) {
            $profilList = Profil::model()->profilTrx()->tipeSupplier()->findAll(array(
                'select' => 'id, nama'
            ));
        } else {
            $profilList = Profil::model()->profilTrx()->findAll(array(
                'select' => 'id, nama'
            ));
        }

        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string.='<option value="' . $profil->id . '">';
            $string.=$profil->nama . '</option>';
        }
        echo $string;
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
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
        $pembelianDetail->setAttribute('pembelian_id', '=' . $id);
        if (isset($_GET['PembelianDetail'])) {
            $pembelianDetail->attributes = $_GET['PembelianDetail'];
        }

        /* Model untuk membuat barang baru */
        $barang = new Barang;

        $pilihBarang = TRUE;
        if (isset($_GET['pilihb']) && $_GET['pilihb'] == FALSE) {
            $pilihBarang = FALSE;
        }

        /* Mengambil nilai pembulatan ke atas untuk harga jual */
        $config = Config::model()->find('nama=:nama', [':nama' => 'pembelian.pembulatankeatashj']);

        $this->render('ubah', array(
            'model' => $model,
            'barangBarcode' => $barangBarcode,
            'barangNama' => $barangNama,
            'pembelianDetail' => $pembelianDetail,
            'barang' => $barang,
            'pilihBarang' => $pilihBarang,
            'pembulatan' => $config->nilai
                //'totalPembelian' => $model->ambilTotal()
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
        if ($model->status == Pembelian::STATUS_DRAFT) {
            PembelianDetail::model()->deleteAll('pembelian_id=' . $id);
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
    public function loadModel($id)
    {
        $model = Pembelian::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Pembelian $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pembelian-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Untuk mengambil informasi barang untuk ditampilkan
     * pada saat input pembelian barang
     */
    public function actionGetBarang()
    {
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

    public function actionTambahBarang($id)
    {
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
    public function actionHapusDetail($id)
    {
        $detail = PembelianDetail::model()->findByPk($id);
        $detail->delete();
    }

    /**
     * Nilai total pembelian dalam text terformat ribuan
     * @param int $id
     */
    public function actionTotal($id)
    {
        $pembelian = $this->loadModel($id);
        $total = array();
        $total['sukses'] = true;
        $total['totalF'] = number_format($pembelian->ambilTotal(), 0, ',', '.');

        $this->renderJSON($total);
    }

    /**
     * Update qty detail pembelian via ajax
     */
    public function actionUpdateQty()
    {
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
    public function actionSimpanPembelian($id)
    {
        $return = array('sukses' => false);
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $pembelian = $this->loadModel($id);
            if ($pembelian->status == Pembelian::STATUS_DRAFT) {
                /*
                 * simpan pembelian jika hanya dan hanya jika status masih draft
                 */
                $return = $pembelian->simpanPembelian();
            }
        }
        $this->renderJSON($return);
    }

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

    function renderLinkToSupplier($data)
    {
        return '<a href="' .
                $this->createUrl('supplier/view', array('id' => $data->profil_id)) . '">' .
                $data->profil->nama . '</a>';
    }

    function actionTambahBarangBaru($id)
    {
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

    public function actionImport()
    {
        if (isset($_POST['nomor'])) {
            $dbAhadPos2 = $_POST['database'];
            $nomor = $_POST['nomor'];
            $pembelianPos2 = Yii::app()->db
                    ->createCommand("
                     SELECT tb.tglTransaksiBeli, s.namaSupplier
                     FROM {$dbAhadPos2}.transaksibeli tb
                     JOIN {$dbAhadPos2}.supplier s on tb.idSupplier = s.idSupplier
                     WHERE idTransaksiBeli = :nomor")
                    ->bindValue(':nomor', $nomor)
                    ->queryRow();
            // print_r($pembelianPos2);
            $profil = Profil::model()->find('nama=:nama', array('nama' => trim($pembelianPos2['namaSupplier'])));
            // print_r($supplier);
            if (!is_null($profil)) {
                $pembelian = new Pembelian;
                $pembelian->profil_id = $profil->id;
                $pembelian->referensi = $nomor;
                $pembelian->tanggal_referensi = date_format(date_create_from_format('Y-m-d', $pembelianPos2['tglTransaksiBeli']), 'd-m-Y'); //$pembelianPos2['tglTransaksiBeli'].' 00:00:00';
                if ($pembelian->save()) {

                    $pembelianDetailPos2 = Yii::app()->db
                            ->createCommand("
                           select db.barcode, hargaBeli, gb.hargaJual, RRP, jumBarangAsli, tglExpire, barang.id
                           from {$dbAhadPos2}.detail_beli db
                           join {$dbAhadPos2}.barang gb on db.barcode = gb.barcode
                           left join barang on db.barcode = barang.barcode
                           where idTransaksiBeli = :nomor
                               ")
                            ->bindValue(':nomor', $nomor)
                            ->queryAll();

                    foreach ($pembelianDetailPos2 as $detailPos2) {
                        // Jika barang.id belum ada, buat data barang baru
                        $barangId = $detailPos2['id'];
                        if (is_null($detailPos2['id'])) {
                            $barangBaru = Yii::app()->db->createCommand("
                        select b.barcode, b.namaBarang, k.namaKategoriBarang, s.namaSatuanBarang, r.namaRak
                        from {$dbAhadPos2}.barang b
                        left join {$dbAhadPos2}.kategori_barang k on b.idKategoriBarang=k.idKategoriBarang
                        left join {$dbAhadPos2}.satuan_barang s on b.idSatuanBarang=s.idSatuanBarang
                        left join {$dbAhadPos2}.rak r on b.idRak = r.idRak
                        where barcode = :barcode
                             ")
                                    ->bindValue(':barcode', $detailPos2['barcode'])
                                    ->queryRow();

                            $kategoriBarang = KategoriBarang::model()->find("nama='{$barangBaru['namaKategoriBarang']}'");
                            if (is_null($kategoriBarang)) {
                                $kategoriId = 1;
                            } else {
                                $kategoriId = $kategoriBarang->id;
                            }

                            $satuanBarang = SatuanBarang::model()->find("nama='{$barangBaru['namaSatuanBarang']}'");
                            if (is_null($satuanBarang)) {
                                $satuanId = 1;
                            } else {
                                $satuanId = $satuanBarang->id;
                            }

                            $rakBarang = RakBarang::model()->find("nama='{$barangBaru['namaRak']}'");
                            if (is_null($rakBarang)) {
                                $rakId = 1;
                            } else {
                                $rakId = $rakBarang->id;
                            }

                            $barang = new Barang;
                            $barang->barcode = $barangBaru['barcode'];
                            $barang->nama = $barangBaru['namaBarang'];
                            $barang->kategori_id = $kategoriId;
                            $barang->satuan_id = $satuanId;
                            $barang->rak_id = $rakId;
                            if ($barang->save()) {
                                $barangId = $barang->id;
                                $supplierBarang = new SupplierBarang;
                                $supplierBarang->barang_id = $barangId;
                                $supplierBarang->supplier_id = $profil->id;
                                $supplierBarang->save();
                            }
                        }
                        $detail = new PembelianDetail;
                        $detail->pembelian_id = $pembelian->id;
                        $detail->barang_id = $barangId;
                        $detail->qty = $detailPos2['jumBarangAsli'];
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

        $modelCsvForm = new UploadCsvPembelianForm;
        $supplierList = Profil::model()->profilTrx()->tipeSupplier()->findAll(array(
            'select' => 'id, nama'
        ));
        if (isset($_POST['UploadCsvPembelianForm'])) {
            $modelCsvForm->attributes = $_POST['UploadCsvPembelianForm'];
            if (!empty($_FILES['UploadCsvPembelianForm']['tmp_name']['csvFile'])) {
                $modelCsvForm->csvFile = CUploadedFile::getInstance($modelCsvForm, 'csvFile');
                $return = $modelCsvForm->simpanCsvKePembelian();
                if ($return['sukses']) {
                    $this->redirect($this->createUrl('ubah', array('id' => $return['pembelianId'], 'pilihb' => FALSE)));
                }
            }
        }
        $this->render('import', array(
            'modelCsvForm' => $modelCsvForm,
            'supplierList' => $supplierList
        ));
    }

    public function getNamaFile($nomor, $print)
    {
        switch ($print) {
            case self::PRINT_PEMBELIAN:
                return "pembelian-{$nomor}";
        }
    }

    public function getText($model, $print)
    {
        switch ($print) {
            case self::PRINT_PEMBELIAN:
                return $model->pembelianText();
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

    public function exportPdf($id, $kertas = Pembelian::KERTAS_A4, $draft = false)
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
         * Pembelian Detail
         */
        $pembelianDetail = PembelianDetail::model()->with('barang')->findAll(array(
            'condition' => "pembelian_id={$id}",
            'order' => 'barang.nama'
        ));

        /*
         * Persiapan render PDF
         */
        $listNamaKertas = Pembelian::listNamaKertas();
        $mPDF1 = Yii::app()->ePdf->mpdf('', $listNamaKertas[$kertas]);
        $viewCetak = '_pdf';
        if ($draft) {
            $viewCetak = '_pdf_draft';
        }
        $mPDF1->WriteHTML($this->renderPartial($viewCetak, array(
                    'modelHeader' => $modelHeader,
                    'branchConfig' => $branchConfig,
                    'profil' => $profil,
                    'pembelianDetail' => $pembelianDetail
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

    public function actionPrintPembelian($id)
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

    public function infoNota($profilId, $noRef, $nominal)
    {
        //$pembelians = Pembelian::model()->findAll('profil_id=:profilId and referensi=:noRef and ')
    }

}
