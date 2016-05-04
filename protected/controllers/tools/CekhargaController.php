<?php

class CekhargaController extends Controller
{

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

    // Uncomment the following methods and override them if needed
    /*
      public function filters()
      {
      // return the filter configuration for this controller, e.g.:
      return array(
      'inlineFilterName',
      array(
      'class'=>'path.to.FilterClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }

      public function actions()
      {
      // return external action classes, e.g.:
      return array(
      'action1'=>'path.to.ActionClass',
      'action2'=>array(
      'class'=>'path.to.AnotherActionClass',
      'propertyName'=>'propertyValue',
      ),
      );
      }
     */
}
