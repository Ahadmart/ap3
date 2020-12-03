<?php

class CetaklabelrakController extends Controller
{
    public function actionIndex()
    {
        $modelForm = new CetakLabelRakForm;

        $profil = new Profil('search');
        $profil->unsetAttributes(); // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $rak = new RakBarang('search');
        $rak->unsetAttributes();
        if (isset($_GET['RakBarang'])) {
            $rak->attributes = $_GET['RakBarang'];
        }

        $labelCetak = new LabelRakCetak('search');
        $labelCetak->unsetAttributes();
        if (isset($_GET['LabelRakCetak'])) {
            $labelCetak->attributes = $_GET['LabelRakCetak'];
            /* simpan ke sesi untuk digunakan cetak label */
            Yii::app()->user->setState('labelKategoriId', $_GET['LabelRakCetak']['kategoriId']);
        }

        $layoutForm = new CetakLabelRakLayoutForm;
        if (isset($_GET['CetakLabelRakLayoutForm'])) {
            $layoutForm->attributes = $_GET['CetakLabelRakLayoutForm'];
            $this->labelRakPdf($layoutForm);
        }

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = $_GET['barcodescan'];
        }

        $this->render('index', [
            'modelForm'   => $modelForm,
            'profil'      => $profil,
            'rak'         => $rak,
            'labelCetak'  => $labelCetak,
            'layoutForm'  => $layoutForm,
            'scanBarcode' => $scanBarcode,
        ]);
    }

    public function namaToko()
    {
        $config = Config::model()->find('nama=:nama', [':nama' => 'toko.nama']);
        return $config->nilai;
    }

    public function labelRakPdf($layout)
    {
        /*
         * Persiapan render PDF
         */
        error_reporting(0); // Masih ada error di library Mpdf. Sembunyikan error dahulu, perbaiki kemudian :senyum
        require_once __DIR__ . '/../../vendor/autoload.php';

        set_time_limit(0);
        $tanggalCetak   = date('dmY His');
        $filterKategori = null;
        if (Yii::app()->user->hasState('labelKategoriId')) {
            $filterKategori = Yii::app()->user->getState('labelKategoriId');
        }
        $barang = is_null($filterKategori) || empty($filterKategori) ? LabelRakCetak::model()->findAll() : LabelRakCetak::model()->with('barang', 'barang.kategori')->findAll('barang.kategori_id=' . $filterKategori);

        $listNamaKertas = CetakLabelRakLayoutForm::listNamaKertas();

        //$mPDF1 = Yii::app()->ePdf->mpdf('utf-8', $listNamaKertas[$layout['kertasId']], 0, '', 7, 7, 7, 7, 9, 9);
        $mpdf = new \Mpdf\Mpdf([
            'mode'          => 'utf-8',
            'format'        => $listNamaKertas[$layout['kertasId']],
            'tempDir'       => __DIR__ . '/../../runtime/',
            'margin_left'   => 7,
            'margin_right'  => 7,
            'margin_top'    => 5,
            'margin_bottom' => 5,
            'margin_header' => 9,
            'margin_footer' => 9,
        ]);
        
        //$mpdf->showImageErrors = true;

        $labelRakView = CetakLabelRakLayoutForm::listView();
        $mpdf->WriteHTML($this->renderPartial($labelRakView[$layout['layoutId']], [
            'barang'       => $barang,
            'namaToko'     => $this->namaToko(),
            'tanggalCetak' => $tanggalCetak,
        ], true
        ));
//        $this->renderPartial($labelRakView[$layout['layoutId']], [
//            'barang'       => $barang,
//            'namaToko'     => $this->namaToko(),
//            'tanggalCetak' => $tanggalCetak,
//        ]
//        );

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("label rak {$tanggalCetak}.pdf", 'I');
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

    public function actionPilihRak($id)
    {
        $rak    = RakBarang::model()->findByPk($id);
        $return = [
            'id'   => $id,
            'nama' => $rak->nama,
        ];
        $this->renderJSON($return);
    }

    public function actionTambahkanBarang()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['CetakLabelRakForm'])) {
            $cetakLabelRakForm             = new CetakLabelRakForm;
            $cetakLabelRakForm->attributes = $_POST['CetakLabelRakForm'];
            $rowAffected                   = $cetakLabelRakForm->inputBarangKeCetak();
            if (!is_null($rowAffected) && $rowAffected > 0) {
                $return = [
                    'sukses'      => true,
                    'rowAffected' => $rowAffected,
                ];
            }
            if ($rowAffected == 0) {
                $return = [
                    'sukses' => false,
                    'error'  => [
                        'code' => 501,
                        'msg'  => 'Tidak ada barang yang ditambahkan',
                    ],
                ];
            }
        }
        $this->renderJSON($return);
    }

    public function actionHapus($id)
    {
        LabelRakCetak::model()->deleteByPk($id);
    }

    public function actionHapusSemua()
    {
        LabelRakCetak::model()->deleteAll();
    }

}
