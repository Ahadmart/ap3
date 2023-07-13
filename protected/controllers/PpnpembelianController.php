<?php

class PpnpembelianController extends Controller
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
            [
                'deny', // deny guest
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
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $model = new PembelianPpn;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PembelianPpn'])) {
            $model->attributes = $_POST['PembelianPpn'];
            echo "Data diterima: ";
            print_r($model->attributes);
            if ($model->save()) {
                //     $this->redirect(['view', 'id' => $model->id]);
                echo "Data berhasil disimpan";
            }
            Yii::app()->end();
        }

        $pembelianModel = new Pembelian('search');
        $pembelianModel->unsetAttributes(); // clear any default values
        if (isset($_GET['Pembelian'])) {
            $pembelianModel->attributes = $_GET['Pembelian'];
        }

        $this->render('tambah', [
            'model'          => $model,
            'pembelianModel' => $pembelianModel,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PembelianPpn'])) {
            $model->attributes = $_POST['PembelianPpn'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        $this->render('ubah', [
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
        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new PembelianPpn('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['PembelianPpn'])) {
            $model->attributes = $_GET['PembelianPpn'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PembelianPpn the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PembelianPpn::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PembelianPpn $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pembelian-ppn-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPilihPembelian($id)
    {
        $pembelian = Pembelian::model()->findByPk($id);
        $return    = [
            'id'       => $id,
            'nomor'    => $pembelian->nomor,
            'profil'   => $pembelian->profil->nama,
            'totalPpn' => $pembelian->ambilTotalPpn()
        ];
        $this->renderJSON($return);
    }
}
