<?php

class SkuController extends Controller
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
        $skuDetail = new SkuDetail('search');
        $skuDetail->unsetAttributes();
        $skuDetail->setAttribute('sku_id', '=' . $id);
        if (isset($_GET['SkuDetail'])) {
            $skuDetail->attributes = $_GET['SkuDetail'];
        }
        $this->render('view', [
            'model'       => $this->loadModel($id),
            'modelDetail' => $skuDetail,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $this->layout = 'box_kecil';
        $model        = new Sku;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sku'])) {
            $model->attributes = $_POST['Sku'];
            if ($model->save()) {
                $this->redirect(['ubah', 'id' => $model->id]);
            }
        }

        $this->render('tambah', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        $skuDetail = new SkuDetail('search');
        $skuDetail->unsetAttributes();
        $skuDetail->setAttribute('sku_id', '=' . $id);
        if (isset($_GET['SkuDetail'])) {
            $skuDetail->attributes = $_GET['SkuDetail'];
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Sku'])) {
            $model->attributes = $_POST['Sku'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        $skuLevel = new SkuLevel('search');
        $skuLevel->unsetAttributes();
        $skuLevel->setAttribute('sku_id', '=' . $id);
        if (isset($_GET['SkuLevel'])) {
            $skuLevel->attributes = $_GET['SkuLevel'];
        }

        $levelMax = SkuLevel::model()->find([
            'select'    => 'MAX(level) as maxLevel',
            'condition' => 'sku_id = :skuId',
            'params'    => [':skuId' => $id],
        ])->maxLevel;

        $this->render('ubah', [
            'model'       => $model,
            'modelDetail' => $skuDetail,
            'modelLevel'  => $skuLevel,
            'levelMax'    => $levelMax,
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
        $model = new Sku('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Sku'])) {
            $model->attributes = $_GET['Sku'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Sku the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Sku::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Sku $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sku-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function renderLinkToView($data)
    {
        $return = '';
        if (isset($data->nama)) {
            $return = '<a href="' .
                $this->createUrl('view', ['id' => $data->id]) . '">' .
                $data->nama . '</a>';
        }
        return $return;
    }

    public function actionTambahBarangList()
    {
        $model = new Barang('search');
        $model->unsetAttributes(); // clear any default values
        $model->setAttribute('status', Barang::STATUS_AKTIF); // default yang tampil
        if (isset($_GET['Barang'])) {
            $model->attributes = $_GET['Barang'];
        }
        $this->renderPartial('_tambahbarang_list', ['model' => $model], false, true);
    }

    public function actionTambahBarang()
    {
        $r = [];

        $id       = $_POST['id'];
        $barangId = $_POST['barangId'];

        if (empty($id) || empty($barangId)) {
            $r = [
                'sukses' => false,
                'err'    => [
                    'msg' => 'modelId atau barangId kosong!',
                ],
            ];
        }

        $detail            = new SkuDetail();
        $detail->sku_id    = $id;
        $detail->barang_id = $barangId;
        if (!$detail->save()) {
            $r = [
                'sukses' => false,
                'err'    => [
                    'msg' => 'Gagal tambah barang',
                ],
            ];
        }
        $r = ['sukses' => true];
        $this->renderJSON($r);
    }

    public function actionHapusDetail($id)
    {
        $detail = SkuDetail::model()->findByPk($id);
        if (!$detail->delete()) {
            throw new Exception('Gagal hapus detail sku');
        }
    }

    public function actionTambahLevelForm($id)
    {
        $skuModel = $this->loadModel($id);
        $skuLevel = new SkuLevel();

        if (isset($_POST['SkuLevel'])) {
            $skuLevel->attributes = $_POST['SkuLevel'];
            // Yii::log("Sku Level: " . var_export($skuLevel, true));

            if ($skuLevel->save()) {
                $this->redirect(['ubah', 'id' => $id]);
            } else {
                throw new CHttpException(500, 'Gagal simpan sku level: ' . serialize($skuLevel->getErrors()));
            }
        }

        $criteria = new CDbCriteria();
        $criteria->compare('sku_id', $id);
        $criteria->order = 'level desc';
        $levelTerakhir   = SkuLevel::model()->find($criteria);
        $levelSekarang   = 1;
        // Yii::log('Level Terakhir: ' . var_export($levelTerakhir, true));
        // Yii::log('Level Sekarang: ' . $levelSekarang);
        $satuanTerakhir = '';
        if (!is_null($levelTerakhir)) {
            $levelSekarang  = $levelTerakhir->level + 1;
            $satuanTerakhir = $levelTerakhir->satuan->nama;
        }
        // Yii::log('Level Sekarang: ' . $levelSekarang);
        $this->renderPartial('_tambahlevel_form', [
            'skuModel'       => $skuModel,
            'model'          => $skuLevel,
            'levelSekarang'  => $levelSekarang,
            'satuanTerakhir' => $satuanTerakhir,
        ], false, true);
    }

    public function actionHapusLevel($id)
    {
        $skuLevel = SkuLevel::model()->findByPk($id);
        // $skuLevel->delete();

        $return = [
            'sukses' => $skuLevel->delete(),
        ];
        $this->renderJSON($return);
    }

    public function renderRasioKonversi($data)
    {
        // Mencari sku level terakhir (terbesar)
        // untuk diambil satuannya
        $skuLevel = SkuLevel::model()->find([
            'condition' => 'sku_id = :skuId and level < :curLevel',
            'params'    => [
                ':skuId'    => $data->sku_id,
                ':curLevel' => $data->level,
            ],
            'order'     => 'level DESC',
        ]);
        // Yii::log(print_r($skuLevel, true));
        $namaSatuan = is_null($skuLevel) ? '' : $skuLevel->satuan->nama;
        $editable   = '<a href="#" class="editable-rasio" data-type="text" data-pk="' . $data->id .
            '" data-url="' . $this->createUrl('updaterasio') . '">' . $data->rasio_konversi . '</a>';
        if ($data->level == 1) {
            return $data->rasio_konversi;
        } else {
            return $editable . ' ' . $namaSatuan;
        }
    }

    public function renderNamaSatuan($data)
    {
        return '<a href="#" class="editable-satuan" data-type="select" data-pk="' . $data->id . '" data-url="' . $this->createUrl('updatesatuan') . '">' . $data->satuan->nama . '</a>';
    }

    public function actionUpdateRasio()
    {
        if (empty($_POST['pk']) && empty($_POST['value'])) {
            throw new CHttpException(500, 'Request tidak lengkap');
        }

        $pk  = $_POST['pk'];
        $val = empty($_POST['value']) ? 0 : $_POST['value'];
        if ($val < 2) {
            throw new CHttpException(500, 'Nilai rasio tidak valid');
        }

        $skuLevel                 = SkuLevel::model()->findByPk($pk);
        $skuLevel->rasio_konversi = $val;

        $return = ['sukses' => false];
        if ($skuLevel->save()) {
            Skulevel::syncJPUAtas($skuLevel->sku_id, $skuLevel->level + 1);
            $return = ['sukses' => true];
        }

        $this->renderJSON($return);
    }

    public function actionUpdateSatuan()
    {
        if (empty($_POST['pk']) && empty($_POST['value'])) {
            throw new CHttpException(500, 'Request tidak lengkap');
        }

        $pk  = $_POST['pk'];
        $val = $_POST['value'];

        $skuLevel            = SkuLevel::model()->findByPk($pk);
        $skuLevel->satuan_id = $val;

        $return = ['sukses' => false];
        if ($skuLevel->save()) {
            $return = ['sukses' => true];
        }

        $this->renderJSON($return);
    }

    public function renderQtyPerUnit($data)
    {
        // Mencari sku level terakhir (terbesar)
        // untuk diambil satuannya
        $skuLevel = null;
        if (!is_null($data->skuLevel)) {
            $skuLevel = SkuLevel::model()->find([
                'condition' => 'sku_id = :skuId and level < :curLevel',
                'params'    => [
                    ':skuId'    => $data->sku_id,
                    ':curLevel' => $data->skuLevel->level,
                ],
                'order'     => 'level DESC',
            ]);
        }
        // Mencari sku level 1
        // untuk diambil satuannya
        $skuLevel1 = SkuLevel::model()->find([
            'condition' => 'sku_id = :skuId and level = 1',
            'params'    => [
                ':skuId'    => $data->sku_id,
            ],
        ]);
        // Yii::log('skuId: ' . $data->sku_id . '; ' . 'satuanId: ' . $data->barang->satuan_id);
        // Yii::log('rasioKonversi: '. $skuLevel->rasio_konversi);
        $namaSatuan = is_null($skuLevel) ? '' : $skuLevel->satuan->nama;
        $r = '';
        if (!is_null($data->skuLevel)) {
            $r = $data->skuLevel->rasio_konversi . ' ' . $namaSatuan;
            if ($data->skuLevel->level >= 3) {
                $r .= ' (' . $data->skuLevel->jumlah_per_unit . ' ' . $skuLevel1->satuan->nama . ')';
            }
        }
        return $r;
    }
}
