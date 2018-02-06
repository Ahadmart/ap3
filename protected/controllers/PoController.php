<?php

class PoController extends Controller
{
    const PROFIL_ALL      = 0;
    const PROFIL_SUPPLIER = Profil::TIPE_SUPPLIER;
    /* ============== */

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
        $this->layout = '//layouts/box_kecil';
        $model        = new Po;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Po'])) {
            $model->attributes = $_POST['Po'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }

        }

        $supplierList = Profil::model()->profilTrx()->tipeSupplier()->orderByNama()->findAll([
            'select' => 'id, nama',
        ]);

        $this->render('tambah', [
            'model'        => $model,
            'supplierList' => $supplierList,
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

        if (isset($_POST['Po'])) {
            $model->attributes = $_POST['Po'];
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
        $model = new Po('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Po'])) {
            $model->attributes = $_GET['Po'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Po the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Po::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Po $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'po-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Untuk render link actionView jika ada nomor, jika belum, string kosong
     * @param obj $data
     * @return string Link ke action view jika ada nomor
     */
    public function renderLinkToView($data)
    {
        $return = '';
        if (isset($data->nomor)) {
            $return = '<a href="' .
            $this->createUrl('view', ['id' => $data->id]) . '">' .
            $data->nomor . '</a>';
        }
        return $return;
    }

    /**
     * render link actionUbah jika belum ada nomor
     * @param obj $data
     * @return string tanggal, beserta link jika masih draft (belum ada nomor)
     */
    public function renderLinkToUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
            $this->createUrl('ubah', ['id' => $data->id, 'uid' => $data->updated_by]) . '">' .
            $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }


    public function actionAmbilProfil($tipe)
    {
        /*
         * Tampilkan daftar sesuai pilihan tipe
         */
        if ($tipe == Profil::TIPE_SUPPLIER) {
            $profilList = Profil::model()->profilTrx()->tipeSupplier()->orderByNama()->findAll(array(
                'select' => 'id, nama'
            ));
        } else {
            $profilList = Profil::model()->profilTrx()->orderByNama()->findAll(array(
                'select' => 'id, nama'
            ));
        }

        /* FIX ME: Pindahkan ke view */
        $string = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string.='<option value="' . $profil->id . '">';
            $string.=$profil->nama . '</option>';
        }
        echo $string;
    }

}
