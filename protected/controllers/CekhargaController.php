<?php

class CekhargaController extends PublicController
{

    public $layout = '//layouts/full_tanpalogin';
    public $titleText = '<i class="fa fa-search fa-fw"></i> Cek Harga';

    public function actionIndex()
    {
        $this->render('index');
    }
    public function actionScreensaver()
    {
        $this->layout = '//layouts/screensaver';
        $config = Config::model()->find("nama = 'toko.nama'");
        $this->render('screensaver',['namaToko'=>$config->nilai]);
    }

    public function actionCekBarcode()
    {
        if ($_POST['cekharga'] && isset($_POST['barcode'])) {
            $barang = Barang::model()->find('barcode=:barcode', array(
                ':barcode' => $_POST['barcode']
            ));
            $return = [
                'sukses' => false,
                'error' => [
                    'code' => 500,
                    'msg' => 'Barang tidak ditemukan'
                ]
            ];
            if (!is_null($barang)) {
                $return = [
                    'sukses' => true,
                    'barcode' => $barang->barcode,
                    'nama' => $barang->nama,
                    'harga' => $barang->getHargaJual()
                ];
            }
            $this->renderJSON($return);
        }
    }

}
