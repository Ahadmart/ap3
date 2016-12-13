<?php

class AkmController extends PublicController
{

    public $layout = '//layouts/nonavbar';
    public $titleText = '<i class="fa fa-shopping-basket fa-fw"></i> Anjungan Kasir Mandiri';

    public function actionIndex()
    {
        $configNamaToko = Config::model()->find('nama=\'toko.nama\'');
        $namaToko = $configNamaToko->nilai;
        $this->render('index', [
            'namaToko' => $namaToko
        ]);
    }

    public function actionInput()
    {
        $this->render('input');
    }

    public function actionScreensaver()
    {
        $this->layout = '//layouts/screensaver';
        $config = Config::model()->find("nama = 'toko.nama'");
        $this->render('screensaver', ['namaToko' => $config->nilai]);
    }

    public function actionTambahbarang()
    {
        //print_r(Yii::app()->request->getUserHostAddress());
        if ($_POST['tambah'] && isset($_POST['barcode'])) {
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

            //$return = 
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
