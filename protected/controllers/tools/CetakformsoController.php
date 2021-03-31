<?php

class CetakformsoController extends Controller
{
    public function actionIndex()
    {
        $this->layout = '//layouts/box_kecil';
        $modelForm    = new CetakStockOpnameForm;
        if (isset($_POST['CetakStockOpnameForm'])) {
            $modelForm->attributes = $_POST['CetakStockOpnameForm'];
            if ($modelForm->validate()) {
                /* Penambahan memory_limit jika file pdf "agak" besar */
                ini_set('memory_limit', '256M');
                set_time_limit(0);
                $this->formSoPdf($modelForm, $modelForm->data());
            }
        }

        $rak = new RakBarang('search');
        $rak->unsetAttributes();
        if (isset($_GET['RakBarang'])) {
            $rak->attributes = $_GET['RakBarang'];
        }

        $this->render('index', [
            'modelForm' => $modelForm,
            'rak'       => $rak,
        ]);
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
        $config = Config::model()->find('nama=:nama', [':nama' => 'toko.nama']);
        return $config->nilai;
    }

    public function kodeToko()
    {
        $config = Config::model()->find('nama=:nama', [':nama' => 'toko.kode']);
        return $config->nilai;
    }

    public function formSoPdf($modelForm, $data)
    {
        // $configShowQtyReturBeli = Config::model()->find("nama='barang.showstokreturbeli'");
        // $showRB                 = $configShowQtyReturBeli->nilai == 1 ? true : false;
        /*
         * Persiapan render PDF
         */
        require_once __DIR__ . '/../../vendor/autoload.php';

        $tanggalCetak   = date('d-m-Y H:i:s');
        $listNamaKertas = CetakStockOpnameForm::listNamaKertas();

        $mpdf = new \Mpdf\Mpdf([
            'mode'         => 'utf-8',
            'format'       => $listNamaKertas[$modelForm->kertas],
            'tempDir'      => __DIR__ . '/../../runtime/',
            'margin_left'  => 7,
            'margin_right' => 7,
            'margin_top'   => 7,
        ]);
        $mpdf->WriteHTML($this->renderPartial(
            '_form_so_pdf',
            [
                'modelForm'    => $modelForm,
                'data'         => $data,
                'tanggalCetak' => $tanggalCetak,
                'toko'         => [
                    'kode' => $this->kodeToko(),
                    'nama' => $this->namaToko(),
                ],
                // 'showQtyReturBeli' => $showRB,
            ],
            true
        ));

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->pagenumSuffix = ' / ';
        // Render PDF
        $mpdf->Output("{$this->namaToko()} form so {$modelForm->namaRak} {$tanggalCetak}.pdf", 'I');
    }
}
