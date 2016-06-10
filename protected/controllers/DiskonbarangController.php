<?php

class DiskonbarangController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('deny', // deny guest
                'users' => array('guest'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->layout = '//layouts/box_kecil';
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new DiskonBarang;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DiskonBarang'])) {
            $model->attributes = $_POST['DiskonBarang'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('tambah', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $this->layout = '//layouts/box_kecil';
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DiskonBarang'])) {
            $model->attributes = $_POST['DiskonBarang'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $id));
        }

        $this->render('ubah', array(
            'model' => $model,
        ));
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new DiskonBarang('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['DiskonBarang']))
            $model->attributes = $_GET['DiskonBarang'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return DiskonBarang the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = DiskonBarang::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param DiskonBarang $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'diskon-barang-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetDataBarang()
    {
        $return = array(
            'sukses' => false
        );
        $barcode = $_POST['barcode'];
        $barang = Barang::model()->find('barcode=:barcode', array(
            ':barcode' => $barcode
        ));

        if (is_null($barang)) {

            $this->renderJSON(array_merge($return, array('error' => array(
                    'code' => '500',
                    'msg' => 'Barang tidak ditemukan'))));
        }
        $return = array(
            'sukses' => true,
            'barangId' => $barang->id,
            'barcode' => $barang->barcode,
            'nama' => $barang->nama,
            'satuan' => $barang->satuan->nama,
            'hargaJual' => $barang->getHargaJual(),
            'hargaJualRaw' => $barang->getHargaJualRaw(),
            'stok' => $barang->getStok()
        );

        $this->renderJSON($return);
    }

    public function actionCariBarang($term)
    {
        $arrTerm = explode(' ', $term);
        $wBarcode = '(';
        $wNama = '(';
        $pBarcode = array();
        $param = array();
        $firstRow = true;
        $i = 1;
        foreach ($arrTerm as $bTerm) {
            if (!$firstRow) {
                $wBarcode.=' AND ';
                $wNama.=' AND ';
            }
            $wBarcode.="barcode like :term{$i}";
            $wNama.="nama like :term{$i}";
            $param[":term{$i}"] = "%{$bTerm}%";
            $firstRow = FALSE;
            $i++;
        }
        $wBarcode .= ')';
        $wNama .= ')';
        //      echo $wBarcode.' AND '.$wNama;
        //      print_r($param);

        $q = new CDbCriteria();
        $q->addCondition("{$wBarcode} OR {$wNama}");
        $q->params = $param;
        $barangs = Barang::model()->findAll($q);

        $r = array();
        foreach ($barangs as $barang) {
            $r[] = array(
                'label' => $barang->nama,
                'value' => $barang->barcode,
                'stok' => is_null($barang->stok) ? 'null' : $barang->stok,
                'harga' => $barang->hargaJual
            );
        }

        $this->renderJSON($r);
    }

    public function renderLinkToView($data)
    {
        $return = '';
        if (!is_null($data->barang)) {
            $return = '<a href="' .
                    $this->createUrl('view', array('id' => $data->id)) . '">' .
                    $data->barang->nama . '</a>';
        } else {
            $return = '<a href="' .
                    $this->createUrl('view', array('id' => $data->id)) . '">[SEMUA BARANG]</a>';
        }
        return $return;
    }

}
