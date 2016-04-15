<?php

class CetaklabelrakController extends Controller
{

    public function actionIndex()
    {
        $modelForm = new CetakLabelRakForm;

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
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
        }

        $layoutForm = new CetakLabelRakLayoutForm;
        if (isset($_GET['CetakLabelRakLayoutForm'])) {
            $layoutForm->attributes = $_GET['CetakLabelRakLayoutForm'];
            $this->labelRakPdf($layoutForm);
        }

        $this->render('index', array(
            'modelForm' => $modelForm,
            'profil' => $profil,
            'rak' => $rak,
            'labelCetak' => $labelCetak,
            'layoutForm' => $layoutForm
        ));
    }

    public function namaToko()
    {
        $config = Config::model()->find('nama=:nama', array(':nama' => 'toko.nama'));
        return $config->nilai;
    }

    public function labelRakPdf($layout)
    {

        /*
         * Persiapan render PDF
         */
        $tanggalCetak = date('dmY His');
        $barang = LabelRakCetak::model()->findAll();
        $listNamaKertas = CetakLabelRakLayoutForm::listNamaKertas();

        $mPDF1 = Yii::app()->ePdf->mpdf('utf-8', $listNamaKertas[$layout['kertasId']], 0, '', 7, 7, 7, 7, 9, 9);
        $mPDF1->WriteHTML($this->renderPartial('_label_rak_pdf', array(
                    'barang' => $barang,
                    'namaToko' => $this->namaToko(),
                    'tanggalCetak' => $tanggalCetak
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->margin_top = 5;
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("Label_rak.pdf", 'I');
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

    public function actionPilihRak($id)
    {
        $rak = RakBarang::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'nama' => $rak->nama,
        );
        $this->renderJSON($return);
    }

    public function actionTambahkanBarang()
    {
        $return = array(
            'sukses' => false,
            'error' => [
                'code' => 500,
                'msg' => 'Sempurnakan input!'
            ]
        );
        if (isset($_POST['CetakLabelRakForm'])) {
            $cetakLabelRakForm = new CetakLabelRakForm;
            $cetakLabelRakForm->attributes = $_POST['CetakLabelRakForm'];
            $rowAffected = $cetakLabelRakForm->inputBarangKeCetak();
            if (!is_null($rowAffected)) {
                $return = array(
                    'sukses' => true,
                    'rowAffected' => $rowAffected
                );
            }
        }
        $this->renderJSON($return);
    }

    public function actionHapus($id)
    {
        LabelRakCetak::model()->deleteByPk($id);
    }

}
