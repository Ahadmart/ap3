<?php

class CetakformsoController extends Controller
{

    public function actionIndex()
    {
        $this->layout = '//layouts/box_kecil';
        $modelForm = new CetakStockOpnameForm;
        if (isset($_POST['CetakStockOpnameForm'])) {
            $modelForm->attributes = $_POST['CetakStockOpnameForm'];
            if ($modelForm->validate()) {
                $this->formSoPdf($modelForm, $modelForm->dataForm());
            }
        }

        $rak = new RakBarang('search');
        $rak->unsetAttributes();
        if (isset($_GET['RakBarang'])) {
            $rak->attributes = $_GET['RakBarang'];
        }

        $this->render('index', [
            'modelForm' => $modelForm,
            'rak' => $rak
        ]);
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

    public function actionGetKategoriOpt()
    {
        $rakId = $_POST['rak-id'];
        $this->renderKategoriOpt($rakId);
    }

    public function renderKategoriOpt($rakId)
    {
        $kategori = CetakStockOpnameForm::getKategoriRak($rakId);
        /* fixme: pindahkan ke view */
        echo '<option>[Semua Kategori]</option>';
        foreach ($kategori as $key => $value) {
            echo '<option value="' . $key . '">' . $value . '</option>';
        }
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

    public function formSoPdf($modelForm, $data)
    {

        /*
         * Persiapan render PDF
         */
        set_time_limit(0);
        $tanggalCetak = date('d-m-Y H:i:s');
        $listNamaKertas = CetakStockOpnameForm::listNamaKertas();

        //$mPDF1 = Yii::app()->ePdf->mpdf('utf-8', $listNamaKertas[$modelForm->kertas], 0, '', 7, 7, 7, 7, 9, 9);
        $mPDF1 = Yii::app()->ePdf->mpdf('utf-8', $listNamaKertas[$modelForm->kertas], 0);
        $mPDF1->WriteHTML($this->renderPartial('_form_so_pdf', array(
                    'modelForm' => $modelForm,
                    'data' => $data,
                    'tanggalCetak' => $tanggalCetak,
                    'toko' => [
                        'kode' => $this->kodeToko(),
                        'nama' => $this->namaToko()
                    ]
                        ), true
        ));

        $mPDF1->SetDisplayMode('fullpage');
        $mPDF1->margin_top = 5;
        $mPDF1->pagenumPrefix = 'Hal ';
        $mPDF1->pagenumSuffix = ' / ';
        // Render PDF
        $mPDF1->Output("{$this->namaToko()} form so {$modelForm->namaRak} {$tanggalCetak}.pdf", 'I');
    }

}
