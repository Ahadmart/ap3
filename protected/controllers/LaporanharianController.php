<?php

class LaporanharianController extends Controller
{

    public $layout = '//layouts/box_kecil';

    /**
     * Cari data berdasarkan tanggal.
     * Jika sudah ada, tampilkan record untuk tanggal tsb untuk diedit
     * Jika belum ada, buat record baru untuk tanggal tsb dan tampilkan untuk diedit
     */
    public function actionCari($tanggal)
    {
        $date = isset($tanggal) ? date_format(date_create_from_format('d-m-Y', $tanggal), 'Y-m-d') : NULL;
        $model = LaporanHarian::model()->find('tanggal=:tanggal', array(':tanggal' => $date));

        if (is_null($model)) {
            $model = new LaporanHarian;
            $model->tanggal = $tanggal;
            if ($model->save()) {
                $this->redirect(array('index', 'id' => $model->id));
            }
        } else {
            $this->redirect(array('index', 'id' => $model->id));
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex($id = null)
    {
        if (isset($id)) {
            $model = $this->loadModel($id);
        } else {
            $model = new LaporanHarian;
        }
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['LaporanHarian'])) {
            $model->attributes = $_POST['LaporanHarian'];
            if ($model->save()) {
                $this->redirect(array('index', 'id' => $model->id));
            }
        }
        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return LaporanHarian the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = LaporanHarian::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param LaporanHarian $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'laporan-harian-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
