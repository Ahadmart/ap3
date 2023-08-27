<?php

class PpnpembelianController extends Controller
{
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
     * Validasi data.
     * @param integer $id the ID of the model to be updated
     */
    public function actionValidasi($id)
    {
        $model = $this->loadModel($id);

        if ($model->status == PembelianPpn::STATUS_VALID) {
            // Jika status sudah valid, diarahkan ke view
            $this->redirect(['view', 'id' => $id]);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PembelianPpn'])) {
            $model->attributes = $_POST['PembelianPpn'];
            $model->status = PembelianPpn::STATUS_VALID;
            $model->setScenario('validasi');
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        $this->render('validasi', [
            'model' => $model,
        ]);
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

    public function renderLinkToValidasi($data)
    {
        $return = '';
        if (isset($data->pembelian)) {
            $return = '<a href="' .
                $this->createUrl('validasi', ['id' => $data->id]) . '">' .
                $data->pembelian->nomor . '</a>';
        }
        return $return;
    }
}
