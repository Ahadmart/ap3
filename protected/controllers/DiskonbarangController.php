<?php

class DiskonbarangController extends Controller
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->layout = '//layouts/box_kecil';
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
        $model        = new DiskonBarang;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DiskonBarang'])) {
            $model->attributes = $_POST['DiskonBarang'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::log('Error simpan Diskon Barang: ' . var_export($model->getErrors(), true), 'info');
            }
        }

        $lv1 = new StrukturBarang('search');
        $lv1->unsetAttributes(); // clear any default values
        $lv1->setAttribute('level', 1); // default yang tampil
        $lv1->setAttribute('status', StrukturBarang::STATUS_PUBLISH);

        $strukturDummy = new StrukturBarang('search');
        $strukturDummy->unsetAttributes(); // clear any default values
        $strukturDummy->setAttribute('level', 0);

        $this->render('tambah', [
            'model' => $model,
            'lv1' => $lv1,
            'strukturDummy' => $strukturDummy,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $this->redirect(['view', 'id' => $id]);
        /* Update: tidak bisa ubah, harus dinonaktifkan dan buat baru */
        $this->layout = '//layouts/box_kecil';
        $model        = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['DiskonBarang'])) {
            $model->attributes = $_POST['DiskonBarang'];
            if ($model->save())
                $this->redirect(['view', 'id' => $id]);
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
    /*
      public function actionHapus($id)
      {
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
      }
     * Update: Diskon tidak bisa dihapus, hanya bisa dinonaktifkan
     *
     */

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model         = new DiskonBarang('search');
        $model->unsetAttributes();  // clear any default values
        $model->status = 1;
        if (isset($_GET['DiskonBarang'])) {
            $model->attributes = $_GET['DiskonBarang'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
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
        $return  = [
            'sukses' => false
        ];
        $barcode = $_POST['barcode'];
        $barang  = Barang::model()->find('barcode=:barcode', [
            ':barcode' => $barcode
        ]);

        if (is_null($barang)) {

            $this->renderJSON(array_merge($return, ['error' => [
                'code' => '500',
                'msg'  => 'Barang tidak ditemukan'
            ]]));
        }
        $return = [
            'sukses'       => true,
            'barangId'     => $barang->id,
            'barcode'      => $barang->barcode,
            'nama'         => $barang->nama,
            'satuan'       => $barang->satuan->nama,
            'hargaJual'    => $barang->getHargaJual(),
            'hargaJualRaw' => $barang->getHargaJualRaw(),
            'hargaBeli'    => $barang->getHargaBeli(),
            'stok'         => $barang->getStok()
        ];

        $this->renderJSON($return);
    }

    public function actionCariBarang($term)
    {
        $arrTerm  = explode(' ', $term);
        $wBarcode = '(';
        $wNama    = '(';
        $pBarcode = [];
        $param    = [];
        $firstRow = true;
        $i        = 1;
        foreach ($arrTerm as $bTerm) {
            if (!$firstRow) {
                $wBarcode .= ' AND ';
                $wNama    .= ' AND ';
            }
            $wBarcode           .= "barcode like :term{$i}";
            $wNama              .= "nama like :term{$i}";
            $param[":term{$i}"] = "%{$bTerm}%";
            $firstRow           = FALSE;
            $i++;
        }
        $wBarcode .= ')';
        $wNama    .= ')';
        //      echo $wBarcode.' AND '.$wNama;
        //      print_r($param);

        $q         = new CDbCriteria();
        $q->addCondition("{$wBarcode} OR {$wNama}");
        $q->params = $param;
        $barangs   = Barang::model()->aktif()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label' => $barang->nama,
                'value' => $barang->barcode,
                'stok'  => is_null($barang->stok) ? 'null' : $barang->stok,
                'harga' => $barang->hargaJual
            ];
        }

        $this->renderJSON($r);
    }

    public function renderLinkToView($data)
    {
        $return = '';
        if (!is_null($data->barang)) {
            $return = '<a href="' .
                $this->createUrl('view', ['id' => $data->id]) . '">' .
                $data->barang->nama . '</a>';
        } else if ($data->tipe_diskon_id == DiskonBarang::TIPE_PROMO_PERKATEGORI) {
            $return = '<a href="' .
                $this->createUrl('view', ['id' => $data->id]) . '">' .
                $data->barangKategori->nama . '</a>';
        } else if ($data->tipe_diskon_id == DiskonBarang::TIPE_PROMO_PERSTRUKTUR) {
            $strukturBarang = StrukturBarang::model()->findByPk($data->barang_struktur_id);
            $return = '<a href="' .
                $this->createUrl('view', ['id' => $data->id]) . '">' .
                $strukturBarang->getFullPath() . '</a>';
        } else {
            $return = '<a href="' .
                $this->createUrl('view', ['id' => $data->id]) . '">[SEMUA BARANG]</a>';
        }
        return $return;
    }

    public function renderBarangBonus($data)
    {
        $text = '';
        if (!is_null($data->barangBonus)) {
            $text = $data->barangBonus->nama . ' (' . $data->barangBonus->barcode . ') ' . $data->barang_bonus_qty . ' x';
            if (!is_null($data->barang_bonus_diskon_nominal)) {
                $hargaJual     = number_format($data->barangBonus->hargaJualRaw, 0, ',', '.');
                $diskonNominal = number_format($data->barang_bonus_diskon_nominal, 0, ',', '.');
                $net           = number_format($data->barangBonus->hargaJualRaw - $data->barang_bonus_diskon_nominal, 0, ',', '.');
                $text          .= "<br />" . $hargaJual . ' - ' . $diskonNominal . ' = ' . $net;
            }
        }
        return $text;
    }

    public function actionAutoExpire()
    {
        $this->renderJSON(DiskonBarang::model()->autoExpire());
    }

    /**
     * Update status diskon dari halaman index via ajax
     */
    public function actionUpdateStatus()
    {
        if (isset($_POST['pk'])) {
            $pk             = $_POST['pk'];
            $status         = $_POST['value'];
            $diskon         = DiskonBarang::model()->findByPk($pk);
            $diskon->status = $status;

            $return = ['sukses' => false];
            if ($diskon->save()) {
                $return = ['sukses' => true];
            }

            $this->renderJSON($return);
        }
    }

    public function actionRenderStrukturGrid()
    {
        $level  = Yii::app()->request->getPost('level');
        $parent = Yii::app()->request->getPost('parent');
        switch ($level) {
            case 1:
                $model = new StrukturBarang('search');
                $model->unsetAttributes();
                $model->setAttribute('level', 1);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid1', ['lv1' => $model]);
                break;
            case 2:
                $model = new StrukturBarang('search');
                $model->unsetAttributes(); // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid2', ['lv2' => $model]);
                break;
            case 3:
                $model = new StrukturBarang('search');
                $model->unsetAttributes(); // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid3', ['lv3' => $model]);
                break;
        }
    }
}
