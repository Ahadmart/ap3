<?php

class CetakformsoController extends Controller
{

    public function actionIndex()
    {
        $this->layout = '//layouts/box_kecil';
        $modelForm = new CetakStockOpnameForm;

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
        $kategori = CetakStockOpnameForm::getKategoriRak($rakId);
        /* fixme: pindahkan ke view */
        echo '<option>[Semua Kategori]</option>';
        foreach ($kategori as $kat) {
            echo '<option value="' . $kat['kategori_id'] . '">' . $kat['nama'] . '</option>';
        }
    }

}
