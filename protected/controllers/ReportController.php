<?php

use Mpdf\Tag\Em;

class ReportController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionPilihProfil($id)
    {
        $profil = Profil::model()->findByPk($id);
        $return = [
            'id'      => $id,
            'nama'    => $profil->nama,
            'alamat1' => $profil->alamat1,
        ];
        $this->renderJSON($return);
    }

    /**
     * Report Pembelian Form
     */
    public function actionPembelian()
    {
        $model  = new ReportPembelianForm;
        $report = null;
        if (isset($_POST['ReportPembelianForm'])) {
            $model->attributes = $_POST['ReportPembelianForm'];
            if ($model->validate()) {
                $report = $model->reportPembelian();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }
        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $optionPrinters       = [];
        // Untuk render html select option
        foreach ($printers as $printer) {
            switch ($printer['tipe_id']) {
                case Device::TIPE_PDF_PRINTER:
                    // foreach ($kertasPdf as $key => $value) {
                    //     $optionPrinters['PDF'][$printer['id'] . '|' . $key] = $value;
                    // }
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $optionPrinters[$printer['id']] = $printer['nama'];
                    break;
                default:
                    // Nothing... yet
                    break;
            }
        };
        $this->render('pembelian', [
            'model'          => $model,
            'profil'         => $profil,
            'report'         => $report,
            'printers'       => $printers,
            'optionPrinters' => $optionPrinters,
            'printHandle'    => 'printpembelian',
        ]);
    }

    /**
     * Report Penjualan Form
     */
    public function actionPenjualan()
    {
        $model  = new ReportPenjualanForm;
        $report = [];
        if (isset($_POST['ReportPenjualanForm'])) {
            $model->attributes = $_POST['ReportPenjualanForm'];
            if ($model->validate()) {
                $report = $model->reportPenjualan();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        //$kertasUntukPdf = ReportPenjualanForm::listKertas();
        $this->render('penjualan', [
            'model'    => $model,
            'profil'   => $profil,
            'user'     => $user,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionPrintPenjualan()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    // $this->hutangPiutangPdf($_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->penjualanCsv();
                    break;
            }
        }
    }

    public function penjualanCsv()
    {
        $reportPenjualan = new ReportPenjualanForm;
        $csv             = $reportPenjualan->toCsv();

        if (is_null($csv)) {
            throw new Exception('Tidak ada data', 500);
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Penjualan {$namaToko->nilai} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    /**
     * Untuk menampilkan link nama_lengkap, untuk pilih user
     * @param obj $data
     * @return text link pilih user
     */
    public function renderLinkPilihUser($data)
    {
        $return = '';
        if (isset($data->nama)) {
            $return = '<a href="' .
                $this->createUrl('pilihuser', ['id' => $data->id]) .
                '" class="pilih user">' . $data->nama_lengkap . '</a>';
        }
        return $return;
    }

    public function actionPilihUser($id)
    {
        $user   = User::model()->findByPk($id);
        $return = [
            'id'          => $id,
            'namaLengkap' => $user->nama_lengkap,
            'nama'        => $user->nama,
        ];
        $this->renderJSON($return);
    }

    /**
     * Report Harian Detail Form
     */
    public function actionHarianDetail()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new ReportHarianForm;

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasPdf            = ReportHarianForm::listKertas();

        $this->render('harian', [
            'model'       => $model,
            'judul'       => 'Harian Detail',
            'printers'    => $printers,
            'kertasPdf'   => $kertasPdf,
            'printHandle' => 'printharian',
        ]);
    }

    /**
     * Report Harian Detail Form
     */
    public function actionHarianDetail2()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new ReportHarianForm;

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasPdf            = ReportHarianForm::listKertas();

        $this->render('harian', [
            'model'       => $model,
            'judul'       => 'Harian Detail',
            'printers'    => $printers,
            'kertasPdf'   => $kertasPdf,
            'printHandle' => 'printharian2',
        ]);
    }

    public function actionPrintHarian($printId, $kertas, $tanggal, $invGroup, $keuGroup)
    {
        $model                      = new ReportHarianForm;
        $model->tanggal             = $tanggal;
        $model->trxInvGroupByProfil = $invGroup;
        $model->trxKeuGroupByProfil = $keuGroup;
        if ($model->validate()) {
            $report             = $model->reportHarianDetail();
            $report['tanggal']  = $tanggal;
            $report['namaToko'] = $this->namaToko();
            $report['kodeToko'] = $this->kodeToko();
            $device             = Device::model()->findByPk($printId);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    $this->harianDetailPdf($report, $kertas);
                    break;
            }
            Yii::app()->end();
        }
    }

    public function actionPrintHarian2($printId, $kertas, $tanggal, $invGroup, $keuGroup)
    {
        $model                      = new ReportHarianForm;
        $model->tanggal             = $tanggal;
        $model->trxInvGroupByProfil = $invGroup;
        $model->trxKeuGroupByProfil = $keuGroup;
        if ($model->validate()) {
            $report             = $model->reportHarianDetail();
            $report['tanggal']  = $tanggal;
            $report['namaToko'] = $this->namaToko();
            $report['kodeToko'] = $this->kodeToko();
            $device             = Device::model()->findByPk($printId);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    $this->harianDetailPdf2($report, $kertas);
                    break;
            }
            Yii::app()->end();
        }
    }

    /**
     * Report Harian Rekap Form
     */
    public function actionHarianRekap()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new ReportHarianForm;
        if (isset($_REQUEST['ReportHarianForm'])) {
            $model->attributes = $_REQUEST['ReportHarianForm'];
            if ($model->validate()) {
                $report             = $model->reportHarianRekap();
                $report['tanggal']  = $model->tanggal;
                $report['namaToko'] = $this->namaToko();
                $report['kodeToko'] = $this->kodeToko();
                $this->harianRekapPdf($report);
                Yii::app()->end();
            }
        }

        $this->render('harian', [
            'model' => $model,
            'judul' => 'Harian Rekap',
        ]);
    }

    public function namaToko()
    {
        $config = Config::model()->find('nama=:nama', [':nama' => 'toko.nama']);
        return $config->nilai;
    }

    public function kodeToko()
    {
        $config = Config::model()->find('nama=:nama', [':nama' => 'toko.kode']);
        return $config->nilai;
    }

    public function harianDetailPdf($report, $kertas)
    {
        /*
         * Persiapan render PDF
         */

        require_once __DIR__ . '/../vendor/autoload.php';
        $listNamaKertas = ReportHarianForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('harian_detail_pdf', [
            'report' => $report,
        ], true));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function harianDetailPdf2($report, $kertas)
    {
        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $listNamaKertas = ReportHarianForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('harian_detail_pdf_2', [
            'report' => $report,
        ], true));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function harianRekapPdf($report)
    {
        /*
         * Persiapan render PDF
         */
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->WriteHTML($this->renderPartial('harian_detail_pdf', [
            'report' => $report,
        ], true));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function actionPoinMember()
    {
        $model  = new ReportPoinMemberForm;
        $report = null;
        if (isset($_POST['ReportPoinMemberForm'])) {
            $model->attributes = $_POST['ReportPoinMemberForm'];
            $report            = $model->ambilDataPoinMember();
        }

        $kertasUntukPdf = ReportPoinMemberForm::listKertas();
        $this->render('poinmember', [
            'model'       => $model,
            'judul'       => 'Poin Member',
            'listPeriode' => $model->listPeriode(),
            'listSortBy'  => $model->listSortBy(),
            'report'      => $report,
            'kertasPdf'   => $kertasUntukPdf,
        ]);
    }

    public function actionPoinMemberPdf()
    {
        $model  = new ReportPoinMemberForm;
        $report = null;

        if (isset($_POST['ReportPoinMemberForm'])) {
            $model->attributes = $_POST['ReportPoinMemberForm'];
            $report            = $model->ambilDataPoinMember();
        } else {
            throw new Exception('Tidak ada data, klik lagi dari tombol cetak', 500);
        }

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $waktuCetak     = date('dmY His');
        $listNamaKertas = ReportPoinMemberForm::listNamaKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$model->kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_poin_member_pdf', [
            'report'     => $report,
            'config'     => $branchConfig,
            'waktuCetak' => $waktuCetak,
        ], true));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Poin Member {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function actionTotalStok()
    {
        $this->layout = '//layouts/box_kecil';
        $this->render('totalstok', [
            'stokNet'       => InventoryBalance::model()->totalInventory(),
            'stokReturBeli' => InventoryBalance::model()->totalNilaiReturBeliPosted(),
            'totalStok'     => (int) InventoryBalance::model()->totalInventory() + (int) InventoryBalance::model()->totalNilaiReturBeliPosted(),
        ]);
    }

    public function actionTopRank()
    {
        $model  = new ReportTopRankForm();
        $report = null;
        if (isset($_POST['ReportTopRankForm'])) {
            $model->attributes = $_POST['ReportTopRankForm'];
            if ($model->validate()) {
                $report = $model->reportTopRank();
                // var_dump($report);
                // Yii::app()->end();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER, Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasUntukPdf       = ReportTopRankForm::listKertas();

        $this->render('toprank', [
            'model'     => $model,
            'profil'    => $profil,
            'report'    => $report,
            'printers'  => $printers,
            'kertasPdf' => $kertasUntukPdf,
        ]);
    }

    public function actionPrintTopRank()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    $this->topRankPdf($_GET['profilId'], $_GET['dari'], $_GET['sampai'], $_GET['kategoriId'], $_GET['rakId'], $_GET['limit'], $_GET['sortBy'], $_GET['strukLv1'], $_GET['strukLv2'], $_GET['strukLv3'], $_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->topRankCsv($_GET['profilId'], $_GET['dari'], $_GET['sampai'], $_GET['kategoriId'], $_GET['rakId'], $_GET['limit'], $_GET['sortBy'], $_GET['strukLv1'], $_GET['strukLv2'], $_GET['strukLv3']);
                    break;
            }
        }
    }

    public function topRankPdf($profilId, $dari, $sampai, $kategoriId, $rakId, $limit, $sortBy, $strukLv1, $strukLv2, $strukLv3, $kertas)
    {
        $model             = new ReportTopRankForm;
        $model->profilId   = $profilId;
        $model->dari       = $dari;
        $model->sampai     = $sampai;
        $model->kategoriId = $kategoriId;
        $model->rakId      = $rakId;
        $model->limit      = $limit;
        $model->sortBy     = $sortBy;
        $model->strukLv1   = $strukLv1;
        $model->strukLv2   = $strukLv2;
        $model->strukLv3   = $strukLv3;

        $report = $model->reportTopRank();

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $waktuCetak     = date('dmY His');
        $listNamaKertas = ReportTopRankForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_toprank_pdf', [
            'model'      => $model,
            'report'     => $report,
            'config'     => $branchConfig,
            'waktuCetak' => $waktuCetak,
        ], true));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Top Rank {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function topRankCsv($profilId, $dari, $sampai, $kategoriId, $rakId, $limit, $sortBy, $strukLv1, $strukLv2, $strukLv3)
    {
        $model             = new ReportTopRankForm;
        $model->profilId   = $profilId;
        $model->dari       = $dari;
        $model->sampai     = $sampai;
        $model->kategoriId = $kategoriId;
        $model->rakId      = $rakId;
        $model->limit      = $limit;
        $model->sortBy     = $sortBy;
        $model->strukLv1   = $strukLv1;
        $model->strukLv2   = $strukLv2;
        $model->strukLv3   = $strukLv3;

        $text      = $model->toCsv();
        $namaStruk = '';
        if (!empty($model->strukLv1)) :
            $strukLv1 = StrukturBarang::model()->findByPk($model->strukLv1);
            $namaStruk .= $strukLv1->nama;
        endif;
        if (!empty($model->strukLv2)) :
            $strukLv2 = StrukturBarang::model()->findByPk($model->strukLv2);
            $namaStruk .= '_' . $strukLv2->nama;
        endif;
        if (!empty($model->strukLv3)) :
            $strukLv3 = StrukturBarang::model()->findByPk($model->strukLv3);
            $namaStruk .= '_' . $strukLv3->nama;
        endif;
        $timeStamp  = date('Ymd His');
        $keterangan = $model->dari . '-' . $model->sampai;
        $namaFile   = "Laporan Top Rank {$namaStruk} {$keterangan} {$timeStamp}.csv";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $text,
        ]);
    }

    public function actionHutangPiutang()
    {
        $model  = new ReportHutangPiutangForm();
        $report = null;
        if (isset($_POST['ReportHutangPiutangForm'])) {
            $model->attributes = $_POST['ReportHutangPiutangForm'];
            $model->pilihCetak = $_POST['ReportHutangPiutangForm']['pilihCetak'];
            if ($model->validate()) {
                $report = $model->reportHutangPiutang();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER, Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasUntukPdf       = ReportHutangPiutangForm::listKertas();
        $this->render('hutangpiutang', [
            'model'      => $model,
            'profil'     => $profil,
            'report'     => $report,
            'listAsalHP' => HutangPiutang::model()->listNamaAsal(),
            'printers'   => $printers,
            'kertasPdf'  => $kertasUntukPdf,
        ]);
    }

    public function actionPrintHutangPiutang()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    $this->hutangPiutangPdf($_GET['profilId'], $_GET['showDetail'], $_GET['pilihCetak'], $_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->hutangPiutangCsv($_GET['profilId'], $_GET['showDetail'], $_GET['pilihCetak']);
                    break;
            }
        }
    }

    public function hutangPiutangPdf($profilId, $showDetail, $pilihCetak, $kertas)
    {
        $model  = new ReportHutangPiutangForm;
        $report = null;

        if (isset($profilId)) {
            $model->profilId   = $profilId;
            $model->showDetail = $showDetail;
            $model->pilihCetak = $pilihCetak;
            $report            = $model->reportHutangPiutang();
        } else {
            throw new Exception('Tidak ada data', 500);
        }

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../vendor/autoload.php';
        $waktu          = date('Y-m-d H:i:s');
        $waktuCetak     = date_format(date_create_from_format('Y-m-d H:i:s', $waktu), 'dmY His');
        $listNamaKertas = ReportHutangPiutangForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_hutangpiutang_pdf', [
            'model'      => $model,
            'report'     => $report,
            'config'     => $branchConfig,
            'listAsalHP' => HutangPiutang::model()->listNamaAsal(),
            'waktu'      => $waktu,
            'waktuCetak' => $waktuCetak,
            'profil'     => Profil::model()->findByPk($model->profilId),
        ], true));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Hutang Piutang {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function hutangPiutangCsv($profilId, $showDetail, $pilihCetak)
    {
        $model = new ReportHutangPiutangForm;
        $csv   = null;

        if (isset($profilId)) {
            $model->profilId   = $profilId;
            $model->showDetail = $showDetail;
            $model->pilihCetak = $pilihCetak;
            $csv               = $model->reportHutangPiutangCsv();
        } else {
            throw new Exception('Tidak ada data', 500);
        }
        $profil = Profil::model()->findByPk($profilId);

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "HP {$namaToko->nilai} {$profil->nama} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    public function actionRekapHutangPiutang()
    {
        $model  = new ReportRekapHutangPiutangForm;
        $report = null;
        if (isset($_POST['tombol_submit'])) {
            $report = $model->reportRekapHutangPiutang();
        }

        $kertasUntukPdf = ReportRekapHutangPiutangForm::listKertas();
        $this->render('rekaphutangpiutang', [
            'model'     => $model,
            'report'    => $report,
            'kertasPdf' => $kertasUntukPdf,
        ]);
    }

    public function actionPengeluaranPenerimaan()
    {
        $model  = new ReportPengeluaranPenerimaanForm;
        $report = [];
        if (isset($_POST['ReportPengeluaranPenerimaanForm'])) {
            $model->attributes = $_POST['ReportPengeluaranPenerimaanForm'];
            if ($model->validate()) {
                $report = $model->reportPengeluaranPenerimaan();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $itemKeuangan = new ItemKeuangan('search');
        $itemKeuangan->unsetAttributes(); // clear any default values
        //$itemKeuangan->parent_id = '>0'; //Uncomment jika hanya menampilkan item "anak"
        /* Uncomment jika ingin trx diluar trx inventory
         * fix me: masukkan ke config.
         */
        //$itemKeuangan->id = '>' . ItemKeuangan::ITEM_TRX_SAJA;
        if (isset($_GET['ItemKeuangan'])) {
            $itemKeuangan->attributes = $_GET['ItemKeuangan'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);

        $this->render('pengeluaranpenerimaan', [
            'model'        => $model,
            'profil'       => $profil,
            'itemKeuangan' => $itemKeuangan,
            'report'       => $report,
            'printers'     => $printers,
        ]);
    }

    public function renderLinkPilihItemKeu($data)
    {
        $return = '';
        if (isset($data->nama)) {
            $return = '<a href="' .
                $this->createUrl('pilihitemkeu', ['id' => $data->id]) .
                '" class="pilih itemkeu">' . $data->nama . '</a>';
        }
        return $return;
    }

    public function actionPilihItemKeu($id)
    {
        $itemKeuangan = ItemKeuangan::model()->findByPk($id);
        $return       = [
            'id'     => $id,
            'parent' => isset($itemKeuangan->parent) ? $itemKeuangan->parent->nama : '-',
            'nama'   => $itemKeuangan->nama,
        ];
        $this->renderJSON($return);
    }

    public function actionUmurBarang()
    {
        $model  = new ReportUmurBarangForm;
        $report = null;
        if (isset($_POST['ReportUmurBarangForm'])) {
            $model->attributes = $_POST['ReportUmurBarangForm'];
            if ($model->validate()) {
                $report = $model->reportUmurBarang();
            }
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER, Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasUntukPdf       = ReportUmurBarangForm::listKertas();
        $this->render('umurbarang', [
            'model'     => $model,
            'report'    => $report,
            'printers'  => $printers,
            'kertasPdf' => $kertasUntukPdf,
        ]);
    }

    public function actionPrintUmurBarang()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    $this->umurBarangPdf($_GET['bulan'], $_GET['dari'], $_GET['sampai'], $_GET['kategoriId'], $_GET['limit'], $_GET['sortBy0'], $_GET['sortBy1'], $_GET['strukLv1'], $_GET['strukLv2'], $_GET['strukLv3'], $_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->umurBarangCsv($_GET['bulan'], $_GET['dari'], $_GET['sampai'], $_GET['kategoriId'], $_GET['limit'], $_GET['sortBy0'], $_GET['sortBy1'], $_GET['strukLv1'], $_GET['strukLv2'], $_GET['strukLv3']);
                    break;
            }
        }
    }

    public function umurBarangCsv($bulan, $dari, $sampai, $kategoriId, $limit, $sortBy0, $sortBy1, $strukLv1, $strukLv2, $strukLv3)
    {
        $model = new ReportUmurBarangForm();

        $model->bulan      = $bulan;
        $model->dari       = $dari;
        $model->sampai     = $sampai;
        $model->kategoriId = $kategoriId;
        $model->limit      = $limit;
        $model->sortBy0    = $sortBy0;
        $model->sortBy1    = $sortBy1;
        $model->strukLv1   = $strukLv1;
        $model->strukLv2   = $strukLv2;
        $model->strukLv3   = $strukLv3;
        //$report            = $model->reportUmurBarang();

        $text      = $model->toCsv();
        $namaStruk = '';
        if (!empty($model->strukLv1)) :
            $strukLv1 = StrukturBarang::model()->findByPk($model->strukLv1);
            $namaStruk .= $strukLv1->nama;
        endif;
        if (!empty($model->strukLv2)) :
            $strukLv2 = StrukturBarang::model()->findByPk($model->strukLv2);
            $namaStruk .= '_' . $strukLv2->nama;
        endif;
        if (!empty($model->strukLv3)) :
            $strukLv3 = StrukturBarang::model()->findByPk($model->strukLv3);
            $namaStruk .= '_' . $strukLv3->nama;
        endif;
        $timeStamp       = date('Ymd His');
        $keteranganWaktu = $model->bulan > 0 ? '>=' . $model->bulan : $model->dari . '-' . $model->sampai;
        $namaFile        = "Laporan Umur Barang {$namaStruk} {$keteranganWaktu} {$timeStamp}.csv";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $text,
        ]);
    }

    public function umurBarangPdf($bulan, $dari, $sampai, $kategoriId, $limit, $sortBy0, $sortBy1, $strukLv1, $strukLv2, $strukLv3, $kertas)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ini_set('pcre.backtrack_limit', '99999999');

        $model = new ReportUmurBarangForm();

        $model->bulan      = $bulan;
        $model->dari       = $dari;
        $model->sampai     = $sampai;
        $model->kategoriId = $kategoriId;
        $model->limit      = $limit;
        $model->sortBy0    = $sortBy0;
        $model->sortBy1    = $sortBy1;
        $model->strukLv1   = $strukLv1;
        $model->strukLv2   = $strukLv2;
        $model->strukLv3   = $strukLv3;
        $report            = $model->reportUmurBarang();

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */

        error_reporting(0); // Masih ada error di library Mpdf. Sembunyikan error dahulu, perbaiki kemudian :senyum
        require_once __DIR__ . '/../vendor/autoload.php';
        $waktu          = date('Y-m-d H:i:s');
        $waktuCetak     = date_format(date_create_from_format('Y-m-d H:i:s', $waktu), 'dmY His');
        $listNamaKertas = ReportUmurBarangForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_umurbarang_pdf', [
            'model'      => $model,
            'report'     => $report,
            'config'     => $branchConfig,
            'waktu'      => $waktu,
            'waktuCetak' => $waktuCetak,
        ], true));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Laporan Umur Barang {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function actionPls()
    {
        $model  = new ReportPlsForm();
        $report = null;
        if (isset($_POST['ReportPlsForm'])) {
            $model->attributes = $_POST['ReportPlsForm'];
            if ($model->validate()) {
                $report = $model->reportPls();
            } else {
                throw new CHttpException(500, 'Gagal mengambil report PLS');
            }
        }
        // echo '<pre>';
        // print_r($report);
        // echo '</pre>';
        // Yii::app()->end();
        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasUntukPdf       = ReportPlsForm::listKertas();
        $this->render('pls', [
            'model'     => $model,
            'profil'    => $profil,
            'report'    => $report,
            'printers'  => $printers,
            'kertasPdf' => $kertasUntukPdf,
        ]);
    }

    public function actionPrintPls()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    $this->plsPdf($_GET['jumlahHari'], $_GET['profilId'], $_GET['orderPeriod'], $_GET['sortBy'], $_GET['kertas']);
                    break;
            }
        }
    }

    public function plsPdf($jumlahHari, $profilId, $orderPeriod, $sortBy, $kertas)
    {
        /* Agar tetap muncul, walaupun "agak" lama */
        ini_set('memory_limit', '-1');
        set_time_limit(300);

        $model = new ReportPlsForm();

        $model->jumlahHari  = $jumlahHari;
        $model->profilId    = $profilId;
        $model->orderPeriod = $orderPeriod;
        $model->sortBy      = $sortBy;
        $report             = $model->reportPls();

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        ini_set('pcre.backtrack_limit', 10000000); // Persiapan jika rownya banyak
        require_once __DIR__ . '/../vendor/autoload.php';
        $waktu          = date('Y-m-d H:i:s');
        $waktuCetak     = date_format(date_create_from_format('Y-m-d H:i:s', $waktu), 'dmY His');
        $listNamaKertas = ReportPlsForm::listKertas();
        $mpdf           = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_pls_pdf', [
            'model'      => $model,
            'report'     => $report,
            'config'     => $branchConfig,
            'waktu'      => $waktu,
            'waktuCetak' => $waktuCetak,
        ], true));
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("PLS {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function actionKartuStok()
    {
        $model  = new ReportKartuStokForm();
        $report = null;
        if (isset($_POST['ReportKartuStokForm'])) {
            $model->attributes = $_POST['ReportKartuStokForm'];
            $model->sortBy     = ReportKartuStokForm::SORT_BY_TANGGAL_ASC;
            if ($model->validate()) {
                $report = $model->reportKartuStok();
            }
        }

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = $_GET['barcodescan'];
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasUntukPdf       = ReportKartuStokForm::listKertas();
        $this->render('kartustok', [
            'model'       => $model,
            'report'      => $report,
            'printers'    => $printers,
            'kertasPdf'   => $kertasUntukPdf,
            'scanBarcode' => $scanBarcode,
        ]);
    }

    public function actionCariBarang($term)
    {
        $q = new CDbCriteria();
        $q->addCondition('barcode like :term OR nama like :term');
        $q->order  = 'nama';
        $q->params = [':term' => "%{$term}%"];
        $barangs   = Barang::model()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label' => $barang->nama,
                'value' => $barang->barcode,
                'id'    => $barang->id,
                'stok'  => is_null($barang->stok) ? 'null' : $barang->stok,
                'harga' => $barang->hargaJual,
            ];
        }

        $this->renderJSON($r);
    }

    public function actionRekapPenjualan()
    {
        $model  = new ReportRekapPenjualanForm;
        $report = [];
        if (isset($_POST['ReportRekapPenjualanForm'])) {
            $model->attributes = $_POST['ReportRekapPenjualanForm'];
            if ($model->validate()) {
                $report = $model->reportRekapPenjualan();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $this->render('rekappenjualan', [
            'model'    => $model,
            'profil'   => $profil,
            'user'     => $user,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionDaftarBarang()
    {
        $this->layout = '//layouts/box_kecil';

        $model  = new ReportDaftarBarangForm;
        $report = null;

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $this->render('daftarbarang', [
            'model'    => $model,
            'profil'   => $profil,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionPrintDaftarBarang($printId, $profilId, $hanyaDefault, $filterNama, $sortBy0, $sortBy1)
    {
        $model             = new ReportDaftarBarangForm;
        $model->attributes = [
            'profilId'     => $profilId,
            'hanyaDefault' => $hanyaDefault,
            'filterNama'   => $filterNama,
            'sortBy0'      => $sortBy0,
            'sortBy1'      => $sortBy1,
        ];

        if ($model->validate()) {
            $report = $model->reportDaftarBarang();

            $device = Device::model()->findByPk($printId);
            switch ($device->tipe_id) {
                case Device::TIPE_CSV_PRINTER:
                    $this->daftarBarangCsv($model, $report, $profilId);
                    break;
            }
        } else {
            $msg = [];
            foreach ($model->errors as $error) {
                $msg[] = $error[0];
            }
            /* Tampillkan error validasi yang paling atas dahulu */
            $this->layout = '//layouts/box_kecil';
            $this->render('../app/error', ['code' => 500, 'message' => $msg[0]]);
        }
    }

    public function daftarBarangCsv($model, $report, $profilId)
    {
        $profil     = Profil::model()->findByPk($profilId);
        $namaProfil = is_null($profil) ? 'Semua-Profil' : $profil->nama;
        $namaToko   = Config::model()->find("nama = 'toko.nama'");
        $timeStamp  = date('Y-m-d-H-i');
        $namaFile   = "Daftar Barang_{$namaProfil}_{$namaToko->nilai}_{$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $model->reportKeCsv($report),
        ]);
    }

    /**
     * Report Retur Pembelian Form
     */
    public function actionReturPembelian()
    {
        $model  = new ReportReturPembelianForm;
        $report = [];
        if (isset($_POST['ReportReturPembelianForm'])) {
            $model->attributes = $_POST['ReportReturPembelianForm'];
            if ($model->validate()) {
                $report = $model->reportReturPembelian();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);

        $this->render('returpembelian', [
            'model'    => $model,
            'profil'   => $profil,
            'user'     => $user,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionDiskon()
    {
        $model  = new ReportDiskonForm();
        $report = [];
        if (isset($_POST['ReportDiskonForm'])) {
            $model->attributes = $_POST['ReportDiskonForm'];
            if ($model->validate()) {
                $report = $model->reportDiskon();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);

        $this->render('diskon', [
            'model'    => $model,
            'profil'   => $profil,
            'user'     => $user,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionPrintDiskon()
    {
        $model  = new ReportDiskonForm();
        $report = [];
        if (isset($_GET['printId'])) {
            // Saat ini baru ada csv. Jika ada yang lain, fixme!
            $model->profilId     = $_GET['profilId'];
            $model->userId       = $_GET['userId'];
            $model->tipeDiskonId = $_GET['tipeDiskonId'];
            $model->dari         = $_GET['dari'];
            $model->sampai       = $_GET['sampai'];
            // print_r($model->attributes);
            if ($model->validate()) {
                $report    = $model->reportDiskon();
                $text      = $model->toCsv($report['detail']);
                $timeStamp = date('Ymd His');
                $namaFile  = "Laporan Diskon_{$model->dari}_{$model->sampai}_{$timeStamp}.csv";
                // $contentTypeMeta = 'text/csv';

                $this->renderPartial('_csv', [
                    'namaFile' => $namaFile,
                    'csv'      => $text,
                    // 'contentType' => $contentTypeMeta
                ]);
            }
        }
    }

    public function actionRekapDiskon()
    {
        $model  = new ReportRekapDiskonForm();
        $report = [];
        if (isset($_POST['ReportRekapDiskonForm'])) {
            $model->attributes = $_POST['ReportRekapDiskonForm'];
            if ($model->validate()) {
                $report = $model->reportRekapDiskon();
            }
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER, Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);

        $this->render('rekapdiskon', [
            'model'     => $model,
            'report'    => $report,
            'printers'  => $printers,
            'kertasPdf' => ReportRekapDiskonForm::listKertas(),
        ]);
    }

    public function actionPrintRekapDiskon()
    {
        $model  = new ReportRekapDiskonForm();
        $report = [];
        if (isset($_GET['printId'])) {
            $printId             = $_GET['printId'];
            $model->tipeDiskonId = $_GET['tipeDiskonId'];
            $model->dari         = $_GET['dari'];
            $model->sampai       = $_GET['sampai'];
            // print_r($model->attributes);
            if ($model->validate()) {
                $report = $model->reportRekapDiskon();
                $device = Device::model()->findByPk($printId);
                switch ($device->tipe_id) {
                    case Device::TIPE_CSV_PRINTER:
                        $text      = $model->toCsv($report['detail']);
                        $timeStamp = date('Ymd His');
                        $namaFile  = "Laporan Rekap Diskon_{$model->dari}_{$model->sampai}_{$timeStamp}.csv";
                        $this->renderPartial('_csv', [
                            'namaFile' => $namaFile,
                            'csv'      => $text,
                        ]);
                        break;
                    case Device::TIPE_PDF_PRINTER:
                        $this->rekapDiskonPdf($report, $_GET['kertas'], $_GET['dari'], $_GET['sampai']);
                }
            }
        }
    }

    public function rekapDiskonPdf($report, $kertas, $dari, $sampai)
    {
        /*
         * Persiapan render PDF
         */
        $timeStamp    = date('Ymd His');
        $configs      = Config::model()->findAll();
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }
        $listNamaKertas = ReportRekapDiskonForm::listKertas();
        require_once __DIR__ . '/../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_rekap_diskon_pdf', [
            'report'     => $report,
            'config'     => $branchConfig,
            'waktuCetak' => $timeStamp,
            'dari'       => $dari,
            'sampai'     => $sampai,
        ], true));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Laporan Rekap Diskon_{$dari}-{$sampai}_{$timeStamp}.pdf", 'I');
    }

    public function pengeluaranPenerimaanCsv($dari, $sampai, $itemKeuId, $profilId)
    {
        $model             = new ReportPengeluaranPenerimaanForm;
        $csv               = [];
        $model->attributes = [
            'dari'      => $dari,
            'sampai'    => $sampai,
            'itemKeuId' => $itemKeuId,
            'profilId'  => $profilId,
        ];
        if ($model->validate()) {
            $csv = $model->toCsv();
        }

        if (is_null($csv)) {
            throw new Exception('Tidak ada data', 500);
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Pengeluaran Penerimaan {$namaToko->nilai} {$dari} {$sampai} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    public function actionPrintPengeluaranPenerimaan()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_CSV_PRINTER:
                    $this->pengeluaranPenerimaanCsv($_GET['dari'], $_GET['sampai'], $_GET['itemKeuId'], $_GET['profilId']);
                    break;
            }
        }
    }

    /**
     * Report Penjualan dari Sales Order Form
     */
    public function actionPenjualanSalesOrder()
    {
        $model  = new ReportPenjualanSalesOrderForm;
        $report = [];
        if (isset($_POST['ReportPenjualanSalesOrderForm'])) {
            $model->attributes = $_POST['ReportPenjualanSalesOrderForm'];
            if ($model->validate()) {
                $report = $model->report();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);

        $this->render('penjualan_salesorder', [
            'model'    => $model,
            'profil'   => $profil,
            'user'     => $user,
            'report'   => $report,
            'printers' => $printers,
        ]);
    }

    public function actionPrintPenjualanSo()
    {
        if (isset($_GET['printId'])) {
            $device = Device::model()->findByPk($_GET['printId']);
            switch ($device->tipe_id) {
                case Device::TIPE_CSV_PRINTER:
                    $this->penjualanSoCsv();
                    break;
            }
        }
    }

    /* Render CSV Penjualan dari Sales Order */

    public function penjualanSoCsv()
    {
        $report = new ReportPenjualanSalesOrderForm();
        $csv    = $report->toCsv();

        if (is_null($csv)) {
            throw new Exception('Tidak ada data', 500);
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Penjualan Sales Order {$namaToko->nilai} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    /**
     * Report Penjualan per Kategori Form
     */
    public function actionPenjualanPerKategori()
    {
        $model = new ReportPenjualanPerKategoriForm();
        /*
        $report = [];
        if (isset($_POST['ReportPenjualanPerKategoriForm'])) {
        $model->attributes = $_POST['ReportPenjualanPerKategoriForm'];
        if ($model->validate()) {
        $report = $model->report();
        }
        }
         *
         */

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        // $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER];
        // $printers             = Device::model()->listDevices($tipePrinterAvailable);
        // $kertasUntukPdf = ReportPenjualanForm::listKertas();
        $this->render('penjualan_per_kategori', [
            'model'  => $model,
            'profil' => $profil,
            'user'   => $user,
            //'report'   => $report,
        ]);
    }

    public function actionPenjualanPerKategoriCsv()
    {
        $model  = new ReportPenjualanPerKategoriForm();
        $report = [];
        if (isset($_POST['ReportPenjualanPerKategoriForm'])) {
            $model->attributes = $_POST['ReportPenjualanPerKategoriForm'];
            if ($model->validate()) {
                $report = $model->toCSV();
            }
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Penjualan per Kategori {$namaToko->nilai} {$model->dari}_{$model->sampai} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $report,
        ]);
    }

    public function actionStockOpname()
    {
        $model  = new ReportStockOpnameForm();
        $report = null;
        if (isset($_POST['ReportStockOpnameForm'])) {
            $model->attributes = $_POST['ReportStockOpnameForm'];
            if ($model->validate()) {
                $report = $model->report();
            }
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_CSV_PRINTER, Device::TIPE_PDF_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasPdf            = ReportStockOpnameForm::listKertas();

        $this->render('stockopname', [
            'model'                => $model,
            'user'                 => $user,
            'report'               => $report,
            'printers'             => $printers,
            'kertasPdf'            => $kertasPdf,
            'nilaiDenganHargaJual' => ReportStockOpnameForm::isHitungDenganHargaJual(),
        ]);
    }

    public function actionPrintStockOpname($printId, $dari, $sampai, $user, $kertas = null)
    {
        $model         = new ReportStockOpnameForm();
        $model->dari   = $dari;
        $model->sampai = $sampai;
        $model->userId = $user;
        if ($model->validate()) {
            $report              = $model->report();
            $report['timestamp'] = date('d-m-Y H:i:s');
            $report['namaToko']  = $this->namaToko();
            $report['kodeToko']  = $this->kodeToko();
            $report['dari']      = $dari;
            $report['sampai']    = $sampai;

            $device = Device::model()->findByPk($printId);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    $this->stockOpnamePdf($report, $kertas);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $this->stockOpnameCsv($report);
                    break;
            }
            Yii::app()->end();
        }
    }

    public function stockOpnamePdf($report, $kertas)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        ini_set('pcre.backtrack_limit', '99999999');

        /*
         * Persiapan render PDF
         */
        $listNamaKertas = ReportStockOpnameForm::listKertas();
        require_once __DIR__ . '/../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial('_stockopname_pdf', [
            'report'               => $report,
            'nilaiDenganHargaJual' => ReportStockOpnameForm::isHitungDenganHargaJual(),
        ], true));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Laporan SO {$report['kodeToko']} {$report['namaToko']} {$report['dari']} {$report['sampai']} {$report['timestamp']}.pdf", 'I');
    }

    public function stockOpnameCsv($report)
    {
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Stock Opname_{$report['namaToko']}_{$report['dari']}-{$report['sampai']}_{$timeStamp}";
        $model     = new ReportStockOpnameForm;

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $model->reportKeCsv($report['detail']),
        ]);
    }

    /**
     * Report Penjualan per StrukturForm
     */
    public function actionPenjualanPerStruktur()
    {
        $model = new ReportPenjualanPerStrukturForm;

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes(); // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $tipePrinterAvailable = [Device::TIPE_PDF_PRINTER, Device::TIPE_CSV_PRINTER];
        $printers             = Device::model()->listDevices($tipePrinterAvailable);
        $kertasPdf            = ReportPenjualanPerStrukturForm::listKertas();
        $optionPrinters       = [];
        // Untuk render html select option
        foreach ($printers as $printer) {
            switch ($printer['tipe_id']) {
                case Device::TIPE_PDF_PRINTER:
                    foreach ($kertasPdf as $key => $value) {
                        $optionPrinters['PDF'][$printer['id'] . '|' . $key] = $value;
                    }
                    break;
                case Device::TIPE_CSV_PRINTER:
                    $optionPrinters[$printer['id']] = $printer['nama'];
                    break;
                default:
                    // Nothing... yet
                    break;
            }
        };

        $this->render('penjualanperstruktur', [
            'model'          => $model,
            'profil'         => $profil,
            'user'           => $user,
            'printers'       => $printers,
            'kertasPdf'      => $kertasPdf,
            'optionPrinters' => $optionPrinters,
            'printHandle'    => 'printpenjualanstruktur',
        ]);
    }

    public function actionAmbilStrukturLv2()
    {
        $lv1Id   = $_GET['parent-id'];
        $listLv2 = ReportPenjualanPerStrukturForm::listStrukLv2($lv1Id);
        $r       = '';
        foreach ($listLv2 as $key => $value) {
            $r .= '<option value="' . $key . '">' . $value . '</option>';
        }
        echo $r;
    }

    public function actionAmbilStrukturLv3()
    {
        $lv2Id   = $_GET['parent-id'];
        $listLv3 = ReportPenjualanPerStrukturForm::listStrukLv3($lv2Id);
        $r       = '';
        foreach ($listLv3 as $key => $value) {
            $r .= '<option value="' . $key . '">' . $value . '</option>';
        }
        echo $r;
    }

    public function actionPrintPenjualanStruktur()
    {
        $model  = new ReportPenjualanPerStrukturForm;
        $report = [];
        if (isset($_POST['ReportPenjualanPerStrukturForm'])) {
            $model->attributes = $_POST['ReportPenjualanPerStrukturForm'];
            $printerInput      = explode('|', $model->printer); //0:printerId, 1:kertas (PDF)
            $device            = Device::model()->findByPk($printerInput[0]);
            if (is_null($device)) {
                throw new CHttpException(500, 'Printer tidak ditemukan!');
            };

            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    $model->kertas = $printerInput[1];
                    if ($model->validate()) {
                        if ($model->strukLv3 > 0) {
                            $report = $model->reportDetail();
                            //  print_r($report);
                            $this->penjualanStrukturPdf(
                                $report,
                                3,
                                $model->strukLv3,
                                $model->dari,
                                $model->sampai,
                                $model->kertas,
                                $model->profilId,
                                $model->userId
                            );
                        } elseif ($model->strukLv2 > 0) {
                            $report = $model->reportDetail();
                            // print_r($report);
                            $this->penjualanStrukturPdf(
                                $report,
                                2,
                                $model->strukLv2,
                                $model->dari,
                                $model->sampai,
                                $model->kertas,
                                $model->profilId,
                                $model->userId
                            );
                        } else {
                            $report = $model->reportPerLv2();
                            // echo '<pre>';
                            // print_r($report);
                            // echo '</pre>';
                            $this->penjualanStrukturPdf(
                                $report,
                                0,
                                $model->strukLv1,
                                $model->dari,
                                $model->sampai,
                                $model->kertas,
                                $model->profilId,
                                $model->userId
                            );
                        }
                    }
                    break;
                case Device::TIPE_CSV_PRINTER:
                    if ($model->validate()) {
                        $this->penjualanStrukturCSV($model);
                    }
                    break;
            }
        }
    }

    public function penjualanStrukturPdf($report, $level, $strukturId, $dari, $sampai, $kertas, $profilId, $userId)
    {
        $namaStruktur = '';
        $viewReport   = '_penjualan_perstruktur_perbarang_pdf';
        if ($level == 3 || $level == 2) {
            $struktur     = StrukturBarang::model()->findByPk($strukturId);
            $namaStruktur = $struktur->getFullPath();
        } else {
            if ($strukturId > 0) {
                $struktur     = StrukturBarang::model()->findByPk($strukturId);
                $namaStruktur = $struktur->getFullPath();
            } else {
                $namaStruktur = 'Semua';
            }
            $viewReport = '_penjualan_perstruktur_perlv2_pdf';
        }

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = [];
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        $listNamaKertas = ReportPenjualanPerStrukturForm::listKertas();
        require_once __DIR__ . '/../vendor/autoload.php';
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => $listNamaKertas[$kertas], 'tempDir' => __DIR__ . '/../runtime/']);
        $mpdf->WriteHTML($this->renderPartial($viewReport, [
            'report'       => $report,
            'config'       => $branchConfig,
            'namaStruktur' => $namaStruktur,
            'profilId'     => $profilId,
            'userId'       => $userId,
            'level'        => $level,
            'dari'         => date_format(date_create_from_format('d-m-Y H:i', $dari), 'Y-m-d H:i:s'),
            'sampai'       => date_format(date_create_from_format('d-m-Y H:i', $sampai), 'Y-m-d H:i:s'),
            'waktuCetak'   => date('dmY His'),
        ], true));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumPrefix = 'Hal ';
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("Penjualan Per Struktur [{$branchConfig['toko.nama']}] [{$dari} - {$sampai}].pdf", 'I');
    }

    public function penjualanStrukturCSV($model)
    {
        $csv = $model->reportCSV();

        if (is_null($csv)) {
            throw new CHttpException(500, 'Tidak ada data');
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $timeStamp = date('Y-m-d-H-i');
        $namaFile  = "Penjualan perStruktur {$namaToko->nilai} {$timeStamp}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }

    public function actionMutasiPoin()
    {
        $model = new ReportMutasiPoinForm();

        $this->render('mutasipoin', [
            'model' => $model,
        ]);
    }

    public function actionMutasiKoin()
    {
        $model = new ReportMutasiKoinForm();

        $this->render('mutasikoin', [
            'model' => $model,
        ]);
    }

    public function actionPrintPembelian()
    {
        // Yii::log("Masuk action Print Pembelian");
        if (isset($_POST['ReportPembelianForm'])) {
            // Yii::log("PrintId: " . $_POST['ReportPembelianForm']['printer']);
            $device = Device::model()->findByPk($_POST['ReportPembelianForm']['printer']);
            switch ($device->tipe_id) {
                case Device::TIPE_PDF_PRINTER:
                    /* Ada tambahan parameter kertas untuk tipe pdf */
                    // $this->hutangPiutangPdf($_GET['kertas']);
                    break;
                case Device::TIPE_CSV_PRINTER:
                    // Yii::log("Masuk action Print Pembelian CSV");
                    $this->pembelianCsv($_POST['ReportPembelianForm']);
                    break;
            }
        }
    }

    public function pembelianCsv($formData)
    {
        $reportPembelian = new ReportPembelianForm;
        $reportPembelian->attributes = $formData;
        if (!$reportPembelian->validate()) {
            throw new CHttpException(500, 'Message: '.json_encode($reportPembelian->getErrors()));
        }
        $csv             = $reportPembelian->toCsv();

        Yii::log("Hasil CSV:" . $csv);

        if (is_null($csv)) {
            throw new Exception('Tidak ada data', 500);
        }

        $namaToko  = Config::model()->find("nama = 'toko.nama'");
        $profil = '';
        if (!empty($formData['profilId'])) {
            $profil = ' ' . Profil::model()->findByPk($formData['profilId'])->nama;
        }
        $namaFile  = "Pembelian {$namaToko->nilai} {$formData['dari']} {$formData['sampai']}{$profil}";

        $this->renderPartial('_csv', [
            'namaFile' => $namaFile,
            'csv'      => $csv,
        ]);
    }
}
