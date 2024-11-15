<?php

class SkutransferController extends Controller
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
        $this->layout = '//layouts/box_kecil';

        $model = new SkuTransfer;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['SkuTransfer'])) {
            $model->attributes = $_POST['SkuTransfer'];
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

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        // if (isset($_POST['SkuTransfer'])) {
        //     $model->attributes = $_POST['SkuTransfer'];
        //     if ($model->save()) {
        //         $this->redirect(['view', 'id' => $id]);
        //     }
        // }

        $barangAsal = SkuDetail::model()->findAll('sku_id = :skuId', [':skuId' => $id]);
        $barangAsal = new SkuDetail('search');
        $barangAsal->unsetAttributes(); // clear any default values
        if (isset($_GET['SkuDetail']) && isset($_GET['ajax']) && $_GET['ajax'] == 'barang-asal-grid') {
            $barangAsal->attributes = $_GET['SkuDetail'];
        }
        $barangAsal->setAttribute('sku_id', '=' . $id);

        $barangTujuan = SkuDetail::model()->findAll('sku_id = :skuId', [':skuId' => $id]);
        $barangTujuan = new SkuDetail('search');
        $barangTujuan->unsetAttributes(); // clear any default values
        if (isset($_GET['SkuDetail']) && isset($_GET['ajax']) && $_GET['ajax'] == 'barang-tujuan-grid') {
            $barangTujuan->attributes = $_GET['SkuDetail'];
        }
        $barangAsal->setAttribute('sku_id', '=' . $id);

        $this->render('ubah', [
            'model'        => $model,
            'barangAsal'   => $barangAsal,
            'barangTujuan' => $barangTujuan,
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
        $model = new SkuTransfer('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['SkuTransfer'])) {
            $model->attributes = $_GET['SkuTransfer'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return SkuTransfer the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = SkuTransfer::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param SkuTransfer $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sku-transfer-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionCariSku($term)
    {
        $arrTerm  = explode(' ', $term);
        $wNomor   = '(';
        $wNama    = '(';
        $param    = [];
        $firstRow = true;
        $i        = 1;
        foreach ($arrTerm as $bTerm) {
            if (!$firstRow) {
                $wNomor .= ' AND ';
                $wNama .= ' AND ';
            }
            $wNomor .= "nomor like :term{$i}";
            $wNama .= "nama like :term{$i}";
            $param[":term{$i}"] = "%{$bTerm}%";
            $firstRow           = false;
            $i++;
        }
        $wNomor .= ')';
        $wNama .= ')';

        $q = new CDbCriteria();
        $q->addCondition("{$wNomor} OR {$wNama}");
        $q->params = $param;
        $skus      = Sku::model()->findAll($q);

        $r = [];
        foreach ($skus as $sku) {
            $r[] = [
                'label' => $sku->nama,
                'value' => $sku->nomor,
            ];
        }

        $this->renderJSON($r);
    }

    public function actionGetDataSku()
    {
        $return = [
            'sukses' => false,
        ];
        /*
        if (isset($_POST['nomor'])) {
        $nomor = $_POST['nomor'];
        $sku   = Sku::model()->find('nomor = :nomor', [
        ':nomor' => $nomor,
        ]);

        if (is_null($sku)) {
        $this->renderJSON(array_merge($return, ['error' => [
        'code' => '500',
        'msg'  => 'SKU tidak ditemukan',
        ]]));
        }

        $return = [
        'sukses' => true,
        'skuId'  => $sku->id,
        'nomor'  => $sku->nomor,
        'nama'   => $sku->nama,
        ];
        }
         */
        if (isset($_POST['barcode'])) {
            $barcode = $_POST['barcode'];
            $barang  = Barang::model()->find('barcode = :barcode', [
                ':barcode' => $barcode,
            ]);

            if (is_null($barang)) {
                // Barang tidak ditemukan, coba cari nomor SKU
                $sku = Sku::model()->find('nomor = :nomor', [':nomor' => $barcode]);
                if (is_null($sku)) {
                    $this->renderJSON(array_merge($return, ['error' => [
                        'code' => '500',
                        'msg'  => 'SKU/Barang tidak ditemukan',
                    ]]));
                } else {
                    $return = [
                        'sukses' => true,
                        'skuId'  => $sku->id,
                        'nomor'  => $sku->nomor,
                        'nama'   => $sku->nama,
                    ];
                    $this->renderJSON($return);
                }
                $this->renderJSON(array_merge($return, ['error' => [
                    'code' => '500',
                    'msg'  => 'Barang tidak ditemukan',
                ]]));
            }

            $skuDetail = SkuDetail::model()->find('barang_id = :barangId', [
                ':barangId' => $barang->id,
            ]);

            if (is_null($skuDetail)) {
                $this->renderJSON(array_merge($return, ['error' => [
                    'code' => '500',
                    'msg'  => 'SKU tidak ditemukan',
                ]]));
            }

            $sku = Sku::model()->findByPk($skuDetail->sku_id);

            $return = [
                'sukses' => true,
                'skuId'  => $sku->id,
                'nomor'  => $sku->nomor,
                'nama'   => $sku->nama,
            ];
        }
        $this->renderJSON($return);
    }

    public function actionCariBarang($term)
    {
        $arrTerm  = explode(' ', $term);
        $wBarcode = '(';
        $wNama    = '(';
        $param    = [];
        $firstRow = true;
        $i        = 1;
        foreach ($arrTerm as $bTerm) {
            if (!$firstRow) {
                $wBarcode .= ' AND ';
                $wNama .= ' AND ';
            }
            $wBarcode .= "barcode like :term{$i}";
            $wNama .= "nama like :term{$i}";
            $param[":term{$i}"] = "%{$bTerm}%";
            $firstRow           = false;
            $i++;
        }
        $wBarcode .= ')';
        $wNama .= ')';

        $q = new CDbCriteria();
        $q->addCondition("{$wBarcode} OR {$wNama}");
        $q->params = $param;
        $barangs   = Barang::model()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label' => $barang->nama,
                'value' => $barang->barcode,
            ];
        }

        $wNomor   = '(';
        $wNamaSKU = '(';
        $param    = [];
        $firstRow = true;
        $i        = 1;
        foreach ($arrTerm as $bTerm) {
            if (!$firstRow) {
                $wNomor .= ' AND ';
                $wNamaSKU .= ' AND ';
            }
            $wNomor .= "nomor like :term{$i}";
            $wNamaSKU .= "nama like :term{$i}";
            $param[":term{$i}"] = "%{$bTerm}%";
            $firstRow           = false;
            $i++;
        }
        $wNomor .= ')';
        $wNamaSKU .= ')';

        $q = new CDbCriteria();
        $q->addCondition("{$wNomor} OR {$wNamaSKU}");
        $q->params = $param;
        $skus      = Sku::model()->findAll($q);
        $rSku      = [];
        foreach ($skus as $sku) {
            $rSku[] = [
                'label' => '[SKU] ' . $sku->nama,
                'value' => $sku->nomor,
            ];
        }

        $this->renderJSON(array_merge($rSku, $r));
    }

    public function renderLinkToUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
            $this->createUrl('ubah', ['id' => $data->id]) . '">' .
            date('d-m-Y H:i:s', strtotime($data->tanggal)) . '</a>';
        } else {
            $return = date('d-m-Y H:i:s', strtotime($data->tanggal));
        }
        return $return;
    }

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

    public function actionRenderTujuan()
    {
        $skuDetailId = Yii::app()->request->getPost('dariId');

        $skuDetail = SkuDetail::model()->findByPk($skuDetailId);
        $skuId     = $skuDetail->sku_id;
        $level     = $skuDetail->skuLevel->level;

        $criteria = new CDbCriteria();
        // $criteria->join  = '
        //     JOIN barang br ON br.id = t.barang_id
        //     JOIN sku_level ON sku_level.satuan_id = br.satuan_id AND sku_level.level < :level
        // ';
        $criteria->condition = 't.sku_id = :sku_id AND sku_level.level < :level';
        $criteria->params    = [':sku_id' => $skuId, ':level' => $level];

        // $skuDetails = SkuDetail::model()->findAll($criteria);
        // print_r($skuDetails);

        $model = new SkuDetail('search');
        $this->renderPartial('_ubah_ke', [
            'barangTujuan' => $model,
            'criteria'     => $criteria,
        ]);
    }

    public function actionKonversi()
    {
        $r = [
            'sukses' => false,
            'error'  => [
                'code' => 500,
                'msg'  => 'Request tidak lengkap',
            ],
        ];

        $asalId   = Yii::app()->request->getPost('asalId');
        $tujuanId = Yii::app()->request->getPost('tujuanId');

        $asal = SkuDetail::model()->findByPk($asalId);
        if (is_null($asal)) {
            $err = [
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ditemukan barang asal konversi',
                ],
            ];
            $this->renderJSON($err);
        }

        $tujuan = SkuDetail::model()->findByPk($tujuanId);
        if (is_null($tujuan)) {
            $err = [
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => 'Tidak ditemukan barang tujuan konversi',
                ],
            ];
            $this->renderJSON($err);
        }

        $levelAsal   = $asal->skuLevel->level;
        $levelTujuan = $tujuan->skuLevel->level;

        $rK = SkuLevel::kumulatifRasioKonversi($asal->sku_id, $levelAsal, $levelTujuan);

        $r = [
            'asal'          => $asalId,
            'tujuan'        => $tujuanId,
            // 'l1' => $levelAsal,
            // 'l2' => $levelTujuan,
            'sukses'        => true,
            'rasioKonversi' => $rK,
            'satuanAsal'    => $asal->barang->satuan->nama,
            'satuanTujuan'  => $tujuan->barang->satuan->nama,
        ];
        $this->renderJSON($r);
    }

    public function actionTransfer($id)
    {
        $asalId   = Yii::app()->request->getPost('asalId');
        $tujuanId = Yii::app()->request->getPost('tujuanId');
        $qty      = Yii::app()->request->getPost('qty');

        $skuDetailAsal   = SkuDetail::model()->findByPk($asalId);
        $skuDetailTujuan = SkuDetail::model()->findByPk($tujuanId);
		
		// Insert on duplicate update
        $detail = SkuTransferDetail::model()->find('sku_transfer_id = :id', [':id' => $id]) ?? new SkuTransferDetail();

		$detail->sku_transfer_id = $id;
        $detail->from_barang_id  = $asalId;
        $detail->from_satuan_id  = $skuDetailAsal->barang->satuan_id;
        $detail->from_qty        = $qty;
        $detail->to_barang_id    = $tujuanId;
        $detail->to_satuan_id    = $skuDetailTujuan->barang->satuan_id;
        $detail->to_qty          = $qty * SkuLevel::kumulatifRasioKonversi(
            $skuDetailAsal->sku_id,
            $skuDetailAsal->skuLevel->level,
            $skuDetailTujuan->skuLevel->level
        );

        if (!$detail->save()) {
            throw new Exception('Gagal simpan sku transfer detail', 500);
        }

        $model = $this->loadModel($id);
        $r     = $model->simpan();
    }
}
