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
            $barang = Barang::model()->find('barcode=:barcode', [
                ':barcode' => $_POST['barcode'],
            ]);
            $return = [
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Barang tidak ditemukan',
                ],
            ];
            if (!is_null($barang)) {
                $return = [
                    'sukses'   => true,
                    'barcode'  => $barang->barcode,
                    'nama'     => $barang->nama,
                    'stok'     => $barang->stok,
                    'harga'    => $barang->getHargaJual(),
                    'hj_multi' => HargaJualMulti::listAktif($barang->id),
                ];
            }
            $this->renderJSON($return);
        }
    }

    public function actionCariBarang($term)
    {
        $q = new CDbCriteria();
        $q->addCondition('(barcode like :term OR nama like :term) AND status = :status');
        $q->order  = 'nama';
        $q->params = [':term' => "%{$term}%", ':status' => Barang::STATUS_AKTIF];
        $barangs   = Barang::model()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label'  => $barang->nama,
                'value'  => $barang->barcode,
                'stok'   => is_null($barang->stok) ? 'null' : $barang->stok,
                'harga'  => $barang->hargaJual,
                'status' => $barang->status,
            ];
        }

        $this->renderJSON($r);
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
