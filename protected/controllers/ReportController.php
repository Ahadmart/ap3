<?php

class ReportController extends Controller
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionPilihProfil($id)
    {
        $profil = Profil::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'nama' => $profil->nama,
            'alamat1' => $profil->alamat1,
        );
        $this->renderJSON($return);
    }

    /**
     * Report Pembelian Form
     */
    public function actionPembelian()
    {
        $model = new ReportPembelianForm;
        if (isset($_POST['ReportPembelianForm'])) {
            $model->attributes = $_POST['ReportPembelianForm'];
            if ($model->validate()) {
                $report = $model->reportPembelian();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $this->render('pembelian', array(
            'model' => $model,
            'profil' => $profil
        ));
    }

    /**
     * Report Penjualan Form
     */
    public function actionPenjualan()
    {
        $model = new ReportPenjualanForm;
        $report = array();
        if (isset($_POST['ReportPenjualanForm'])) {
            $model->attributes = $_POST['ReportPenjualanForm'];
            if ($model->validate()) {
                $report = $model->reportPenjualan();
            }
        }

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $user = new User('search');
        $user->unsetAttributes();  // clear any default values
        if (isset($_GET['User'])) {
            $user->attributes = $_GET['User'];
        }

        $this->render('penjualan', array(
            'model' => $model,
            'profil' => $profil,
            'user' => $user,
            'report' => $report,
        ));
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
                    $this->createUrl('pilihuser', array('id' => $data->id)) .
                    '" class="pilih user">' . $data->nama_lengkap . '</a>';
        }
        return $return;
    }

    public function actionPilihUser($id)
    {
        $user = User::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'namaLengkap' => $user->nama_lengkap,
            'nama' => $user->nama,
        );
        $this->renderJSON($return);
    }

    /**
     * Report Harian Detail Form
     */
    public function actionHarianDetail()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new ReportHarianForm;
        if (isset($_REQUEST['ReportHarianForm'])) {
            $model->attributes = $_REQUEST['ReportHarianForm'];
            if ($model->validate()) {
                $report = $model->reportHarianDetail();
                $report['tanggal'] = $model->tanggal;
                $report['namaToko'] = $this->namaToko();
                $report['kodeToko'] = $this->kodeToko();
                $this->harianDetailPdf($report);
                Yii::app()->end();
            }
        }

        $this->render('harian', array(
            'model' => $model,
            'judul' => 'Harian Detail'
        ));
    }

    /**
     * Report Harian Detail Form
     */
    public function actionHarianDetail2()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new ReportHarianForm;
        if (isset($_REQUEST['ReportHarianForm'])) {
            $model->attributes = $_REQUEST['ReportHarianForm'];
            if ($model->validate()) {
                $report = $model->reportHarianDetail();
                $report['tanggal'] = $model->tanggal;
                $report['namaToko'] = $this->namaToko();
                $report['kodeToko'] = $this->kodeToko();
                $this->harianDetailPdf2($report);
                Yii::app()->end();
            }
        }

        $this->render('harian', array(
            'model' => $model,
            'judul' => 'Harian Detail'
        ));
    }

    /**
     * Report Harian Rekap Form
     */
    public function actionHarianRekap()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new ReportHarianForm;
        if (isset($_REQUEST['ReportHarianForm'])) {
            $model->attributes = $_REQUEST['ReportHarianForm'];
            if ($model->validate()) {
                $report = $model->reportHarianRekap();
                $report['tanggal'] = $model->tanggal;
                $report['namaToko'] = $this->namaToko();
                $report['kodeToko'] = $this->kodeToko();
                $this->harianRekapPdf($report);
                Yii::app()->end();
            }
        }

        $this->render('harian', array(
            'model' => $model,
            'judul' => 'Harian Rekap'
        ));
    }

    public function namaToko()
    {
        $config = Config::model()->find('nama=:nama', array(':nama' => 'toko.nama'));
        return $config->nilai;
    }

    public function kodeToko()
    {
        $config = Config::model()->find('nama=:nama', array(':nama' => 'toko.kode'));
        return $config->nilai;
    }

    public function harianDetailPdf($report)
    {

        /*
         * Persiapan render PDF
         */
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->WriteHTML($this->renderPartial('harian_detail_pdf', array(
                    'report' => $report,
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function harianDetailPdf2($report)
    {

        /*
         * Persiapan render PDF
         */
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->WriteHTML($this->renderPartial('harian_detail_pdf_2', array(
                    'report' => $report,
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function harianRekapPdf($report)
    {

        /*
         * Persiapan render PDF
         */
        $mPDF1 = Yii::app()->ePdf->mpdf('', 'A4');
        $mPDF1->WriteHTML($this->renderPartial('harian_detail_pdf', array(
                    'report' => $report,
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Buku Harian {$report['kodeToko']} {$report['namaToko']} {$report['tanggal']}.pdf", 'I');
    }

    public function actionPoinMember()
    {
        $model = new ReportPoinMemberForm;
        $report = null;
        if (isset($_POST['ReportPoinMemberForm'])) {
            $model->attributes = $_POST['ReportPoinMemberForm'];
            $report = $model->ambilDataPoinMember();
        }

        $kertasUntukPdf = ReportPoinMemberForm::listKertas();
        $this->render('poinmember', array(
            'model' => $model,
            'judul' => 'Poin Member',
            'listPeriode' => $model->listPeriode(),
            'listSortBy' => $model->listSortBy(),
            'report' => $report,
            'kertasPdf' => $kertasUntukPdf
        ));
    }

    public function actionPoinMemberPdf()
    {
        $model = new ReportPoinMemberForm;
        $report = null;

        if (isset($_POST['ReportPoinMemberForm'])) {
            $model->attributes = $_POST['ReportPoinMemberForm'];
            $report = $model->ambilDataPoinMember();
        } else {
            throw new Exception("Tidak ada data, klik lagi dari tombol cetak", 500);
        }

        $configs = Config::model()->findAll();
        /*
         * Ubah config (object) jadi array
         */
        $branchConfig = array();
        foreach ($configs as $config) {
            $branchConfig[$config->nama] = $config->nilai;
        }

        /*
         * Persiapan render PDF
         */
        $waktuCetak = date('dmY His');
        $listNamaKertas = ReportPoinMemberForm::listNamaKertas();
        $mPDF1 = Yii::app()->ePdf->mpdf('', $listNamaKertas[$model->kertas]);
        $mPDF1->WriteHTML($this->renderPartial('_poin_member_pdf', array(
                    'report' => $report,
                    'config' => $branchConfig,
                    'waktuCetak' => $waktuCetak
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Poin Member {$branchConfig['toko.nama']} {$waktuCetak}.pdf", 'I');
    }

    public function actionTotalStok()
    {
        $this->layout = '//layouts/box_kecil';
        $this->render('totalstok', ['totalStok' => InventoryBalance::model()->totalInventory()]);
    }

    public function actionTopRank()
    {
        $model = new ReportTopRankForm();
        $this->render('toprank', ['model' => $model]);
    }

}
