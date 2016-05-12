<?php

class CekhargaController extends PublicController
{

    public $layout = '//layouts/full_tanpalogin';

    public function actionIndex()
    {
        $this->render('index');
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
