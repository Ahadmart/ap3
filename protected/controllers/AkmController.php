<?php

class AkmController extends PublicController
{

    const PRINT_STRUK = 0;
    const PRINTER_NAME = 'strukakm';

    public $layout = '//layouts/nonavbar';
    public $titleText = '<i class="fa fa-shopping-basket fa-fw"></i> Anjungan Kasir Mandiri';

    public function actionIndex()
    {
//        echo sprintf('%u', ip2long(Yii::app()->getRequest()->getUserHostAddress()));// Yii::app()->request->getUserHostAddress();
        $configNamaToko = Config::model()->find('nama=\'toko.nama\'');
        $namaToko = $configNamaToko->nilai;
        $this->render('index', [
            'namaToko' => $namaToko
        ]);
    }

    public function actionInput($id = null)
    {
        /* Jika belum ada ID */
        if (is_null($id)) {
            /* Cek apakah sudah ada yang statusnya draft untuk komputer ini */
            $akm = $this->loadDraftModel();
            /* Jika belum ada yang draft, buat baru */
            if (is_null($akm)) {
                $akm = new Akm;
                $akm->save();
            }
            /* Redirect dengan parameter akm ID */
            $this->redirect(['input', 'id' => $akm->id]);
        }
        $akm = $this->loadModel($id);
        //$akmDetail = AkmDetail::model()->findAll('akm_id=:akmId', [':akmId' => $id]);
        $akmDetail = new AkmDetail('search');
        $akmDetail->unsetAttributes();
        $akmDetail->setAttribute('akm_id', '=' . $id);

        $this->render('input', [
            'model' => $akm,
            'akmDetail' => $akmDetail
        ]);
    }

    public function actionScreensaver()
    {
        $this->layout = '//layouts/screensaver';
        $config = Config::model()->find("nama = 'toko.nama'");
        $this->render('screensaver', ['namaToko' => $config->nilai]);
    }

    public function actionTambahbarang($id)
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

            $model = $this->loadModel($id);
            if (!is_null($barang) && $model->status == Akm::STATUS_DRAFT) {
                $return = $model->tambahBarang($barang->barcode, 1);
            }
            $this->renderJSON($return);
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Akm the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Akm::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function loadDraftModel()
    {
        return Akm::model()->find('updated_by=:updatedBy and status=:statusDraft', [
                    ':updatedBy' => ip2long(Yii::app()->request->getUserHostAddress()),
                    ':statusDraft' => Akm::STATUS_DRAFT]);
    }

    public function loadModelDetail($id)
    {
        $model = AkmDetail::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    public function actionTotal($id)
    {
        $model = $this->loadModel($id);
        echo $model->getTotal();
    }

    public function actionQtyPlus($id)
    {
        $modelDetail = AkmDetail::model()->findByPk($id);
        $model = Akm::model()->findByPk($modelDetail->akm_id);
        $return = $model->tambahBarang($modelDetail->barang->barcode, 1);
        $this->renderJSON($return);
    }

    public function actionQtyMin($id)
    {
        $modelDetail = AkmDetail::model()->findByPk($id);
        $model = Akm::model()->findByPk($modelDetail->akm_id);
        $qty = $modelDetail->qty > 1 ? -1 : 0;
        $return = $model->tambahBarang($modelDetail->barang->barcode, $qty);
        $this->renderJSON($return);
    }

    public function actionHapusDetail($id)
    {
        $return = [
            'sukses' => $this->loadModelDetail($id)->delete()
        ];
        $this->renderJSON($return);
    }

    public function actionBatal($id)
    {
        AkmDetail::model()->deleteAll('akm_id=:akmId', [':akmId' => $id]);
        $this->redirect(['index']);
    }

    public function actionSelesai($id)
    {
        $model = $this->loadModel($id);
        $model->simpan();
        
        $this->printLpr($id);
        $this->redirect(['index']);
    }

    public function printLpr($id)
    {
        $model = $this->loadModel($id);
        $text = $model->strukText();
        
        $address = Yii::app()->request->getUserHostAddress();
        $printerName = self::PRINTER_NAME;
        
        $perintahPrinter = "-H {$address} -P {$printerName}";

        $perintah = "echo \"{$text}\" |lpr {$perintahPrinter} -l";
        exec($perintah, $output);
    }

}
