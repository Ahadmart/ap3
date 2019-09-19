<?php

class KasirController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['deny', // deny guest
                'users' => ['guest'],
            ],
        ];
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     */
    public function actionBuka()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new Kasir;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Kasir'])) {
            $model->attributes = $_POST['Kasir'];
            if ($model->save())
                $this->redirect('index');
        }

        $listKasir     = CHtml::listData(User::model()->findAll(['order' => 'nama_lengkap']), 'id', 'nama_lengkap');
        $listPosClient = CHtml::listData(Device::model()->findAll('tipe_id=' . Device::TIPE_POS_CLIENT), 'id', 'nama');

        $this->render('buka', [
            'model'         => $model,
            'listKasir'     => $listKasir,
            'listPosClient' => $listPosClient
        ]);
    }

    /**
     * Tutup Kasir.
     * @param integer $id the ID of the model to be updated
     */
    public function actionTutup($id)
    {
        $this->layout = '//layouts/box_kecil';
        $model        = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $model->total_penjualan = $model->totalPenjualan()['jumlah'];
        $model->total_margin    = $model->totalMargin()['jumlah'];
        $model->total_retur     = $model->totalReturJual()['jumlah'];

        $model->saldo_akhir_seharusnya = $model->saldo_awal + $model->total_penjualan - $model->total_retur;

        if (isset($_POST['Kasir'])) {
            $model->attributes  = $_POST['Kasir'];
            $model->waktu_tutup = date('Y-m-d H:i:s');
            if ($model->save())
                $this->redirect(['index', 'id' => $id]);
        }

        $this->render('tutup', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model             = new Kasir('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Kasir']))
            $model->attributes = $_GET['Kasir'];
        //$model->waktu_tutup = 'isnull';

        $printerLpr = Device::model()->listDevices([Device::TIPE_LPR]);

        $this->render('index', [
            'model'      => $model,
            'printerLpr' => $printerLpr
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Kasir the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Kasir::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Kasir $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'kasir-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Buka cash drawer yang terhubung ke printer lpr
     * @param int $id LPR Printer ID
     */
    public function actionOpenCashDrawer($id)
    {
        $printerLpr = Device::model()->findByPk($id);
        if (!is_null($printerLpr)) {
            $printerLpr->bukaLaciKas();
            $return = [
                'sukses' => true
            ];
        } else {
            $return = [
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Device tidak ditemukan!'
                ]
            ];
        }
        $this->renderJSON($return);
    }

}
