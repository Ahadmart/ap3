<?php

class StockopnameController extends Controller
{
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $detail = new StockOpnameDetail('search');
        $detail->unsetAttributes();
        $detail->setAttribute('stock_opname_id', '=' . $id);
        if (isset($_GET['StockOpnameDetail'])) {
            $detail->attributes = $_GET['StockOpnameDetail'];
        }
        $this->render('view', [
            'model'  => $this->loadModel($id),
            'detail' => $detail,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new StockOpname;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['StockOpname'])) {
            $model->attributes = $_POST['StockOpname'];
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

        if ($model->status != StockOpname::STATUS_DRAFT) {
            $this->redirect(['view', 'id' => $model->id]);
        }

        $manualMode = isset($_GET['manual']) && $_GET['manual'] == true;

        $soDetail = new StockOpnameDetail('search');
        $soDetail->unsetAttributes();
        if (isset($_GET['StockOpnameDetail'])) {
            $soDetail->attributes = $_GET['StockOpnameDetail'];
        }
        $soDetail->setAttribute('stock_opname_id', "{$id}");

        $barang = new Barang('search');
        $barang->unsetAttributes();
        if (isset($_GET['cariBarang'])) {
            $barang->setAttribute('nama', $_GET['namaBarang']);
        }

        if ($manualMode) {
            $barangBelumSO = new Barang('search');
            $barangBelumSO->unsetAttributes();
            $barangBelumSO->aktif()->belumSO($model->id, $model->rak_id);

            if (isset($_GET['Barang'])) {
                $barangBelumSO->attributes = $_GET['Barang'];
            }
        }

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = $_GET['barcodescan'];
        }

        // $configShowQtyReturBeli = Config::model()->find("nama='barang.showstokreturbeli'");
        // $showRB                 = $configShowQtyReturBeli->nilai == 1 ? true : false;

        $this->render('ubah', [
            'model'         => $model,
            'soDetail'      => $soDetail,
            'barang'        => $barang,
            'manualMode'    => $manualMode,
            'barangBelumSO' => $manualMode ? $barangBelumSO : null,
            'scanBarcode'   => $scanBarcode,
            // 'showQtyReturBeli' => $showRB,
        ]);
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == StockOpname::STATUS_DRAFT) {
            StockOpnameDetail::model()->deleteAll('stock_opname_id=:id', [':id' => $id]);
            $model->delete();
        }

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
        $model = new StockOpname('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['StockOpname'])) {
            $model->attributes = $_GET['StockOpname'];
        }

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StockOpname the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StockOpname::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StockOpname $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'stock-opname-form') {
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
                $this->createUrl('ubah', ['id' => $data->id]) . '">' .
                $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

    public function actionScanBarcode($id)
    {
        $return = [
            'sukses' => false,
        ];
        if (isset($_POST['scan'])) {
            $barcode      = $_POST['barcode'];
            $barang       = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);
            $qtySudahSo   = StockOpnameDetail::model()->qtyYangSudahSo($id, $barang->id);
            $inputselisih = $this->loadModel($id)->input_selisih;
            $return       = [
                'sukses'             => true,
                'barcode'            => $barcode,
                'nama'               => $barang->nama,
                'stok'               => $barang->getStok(),
                'qtySudahSo'         => $qtySudahSo,
                'inputselisih'       => $inputselisih,
                'qtyReturBeliPosted' => $barang->qtyReturBeliPosted,
            ];
        }

        $this->renderJSON($return);
    }

    public function actionTambahDetail($id)
    {
        $return = [
            'sukses' => false,
        ];
        if (isset($_POST['tambah'])) {
            $barcode = $_POST['barcode'];
            $barang  = Barang::model()->find('barcode=:barcode', [':barcode' => $barcode]);
            $stok    = $barang->getStok();
            if (isset($_POST['qty'])) {
                $qty = $_POST['qty'];
            } elseif (isset($_POST['selisih'])) {
                $qty = $stok + $_POST['selisih'];
            }

            $rakId = null;
            if (!empty($_POST['rak'])) {
                $rakId = $_POST['rak'];
            }

            // $setInaktif = false;
            if (isset($_POST['setinaktif'])) {
                $setInaktif = $_POST['setinaktif'] == 'true' || $_POST['setinaktif'] == '1' ? true : false;
            } else {
                $setInaktif = false;
            }
            $return = $this->tambahDetailPlus($id, $barang->id, $stok, $qty, $rakId, $setInaktif);
            // $return = [
            //     'barcode' => $barcode,
            //     'stok' => $stok,
            //     'qty' => $qty,
            //     'rakId' => $rakId,
            //     'setInaktif' => $setInaktif,
            // ];
        }
        $this->renderJSON($return);
    }

    /**
     * Tambah detail SO
     * @param int $soId
     * @param int $barangId
     * @param int $qtyTercatat
     * @param int $qtySebenarnya
     * @return array true jika berhasil
     */
    public function tambahDetail($soId, $barangId, $qtyTercatat, $qtySebenarnya)
    {
        $return = [
            'sukses' => false,
        ];
        $detail                  = new StockOpnameDetail;
        $detail->stock_opname_id = $soId;
        $detail->barang_id       = $barangId;
        $detail->qty_tercatat    = is_null($qtyTercatat) ? 0 : $qtyTercatat;
        $detail->qty_sebenarnya  = $qtySebenarnya;
        if ($detail->save()) {
            $return = [
                'sukses' => true,
            ];
        }
        return $return;
    }

    /**
     * Tambah detail + rak dan status SO
     * @param int $soId
     * @param int $barangId
     * @param int $qtyTercatat
     * @param int $qtySebenarnya
     * @param int $rakId
     * @param boolean $setInaktif
     * @return array true jika berhasil
     */
    public function tambahDetailPlus($soId, $barangId, $qtyTercatat, $qtySebenarnya, $rakId, $setInaktif)
    {
        $return = [
            'sukses' => false,
        ];
        $detail                  = new StockOpnameDetail;
        $detail->stock_opname_id = $soId;
        $detail->barang_id       = $barangId;
        $detail->qty_tercatat    = is_null($qtyTercatat) ? 0 : $qtyTercatat;
        $detail->qty_sebenarnya  = $qtySebenarnya;
        $detail->ganti_rak_id    = $rakId;
        $detail->set_inaktif     = $setInaktif === true ? 1 : 0;
        if ($detail->save()) {
            $return = [
                'sukses' => true,
            ];
        } else {
            $error  = json_encode($detail->getErrors());
            $return = [
                'sukses' => false,
                'error'  => [
                    'code' => 500,
                    'msg'  => $error,
                ],
            ];
        }
        return $return;
    }

    public function actionHapusDetail($id)
    {
        $detail = StockOpnameDetail::model()->findByPk($id);
        if (!$detail->delete()) {
            throw new Exception('Gagal hapus detail SO');
        }
    }

    /*
     * Simpan Stock Opname dan proses terkait (inventory)
     */

    public function actionSimpanSo($id)
    {
        $return = ['sukses' => false];
        // cek jika 'simpan' ada dan bernilai true
        if (isset($_POST['simpan']) && $_POST['simpan']) {
            $so = $this->loadModel($id);
            if ($so->status == StockOpname::STATUS_DRAFT) {
                $return = $so->simpanSo();
            }
        }
        $this->renderJSON($return);
    }

    public function renderQtyLinkEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="q"';
        }
        return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
            Yii::app()->controller->createUrl('inputqtymanual') . '"></a>';
    }

    /**
     * Input qty manual via ajax
     */
    public function actionInputQtyManual()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['pk'])) {
            $pk       = $_POST['pk'];
            $qtyInput = $_POST['value'];
            $id       = $_POST['soId'];
            $barang   = Barang::model()->findByPk($pk);

            $return = $this->tambahDetail($id, $barang->id, $barang->getStok(), $qtyInput);
        }

        $this->renderJSON($return);
    }

    public function renderGantiRakLinkEditable($data, $row)
    {
        return CHtml::link('Pilih..', '', [
            'class'      => 'editable-rak',
            'data-type'  => 'select',
            'data-pk'    => $data->id,
            'data-url'   => Yii::app()->controller->createUrl('gantirak'),
            'data-title' => 'Select Rak',
        ]);
    }

    public function actionGantiRak()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['pk'])) {
            $pk     = $_POST['pk'];
            $rakId  = $_POST['value'];
            $barang = Barang::model()->findByPk($pk);
            Barang::model()->updateByPk($pk, ['rak_id' => $rakId]);
            $return = ['sukses' => true];
        }

        $this->renderJSON($return);
    }

    public function renderTombolSetNol($data, $row)
    {
        return CHtml::link('<i class="fa fa-square-o"><i>', Yii::app()->controller->createUrl('setnol'), [
            'data-barangid' => $data->id,
            'class'         => 'tombol-setnol',
        ]);
    }

    public function actionSetNol($id)
    {
        $return = ['sukses' => false];
        if (isset($_POST['barangid'])) {
            $pk     = $_POST['barangid'];
            $barang = Barang::model()->findByPk($pk);
            $return = $this->tambahDetail($id, $barang->id, $barang->getStok(), 0);
        }
        $this->renderJSON($return);
    }

    public function renderTombolSetInAktif($data, $row)
    {
        return CHtml::link('<i class="fa fa-square-o"><i>', Yii::app()->controller->createUrl('setinaktif'), [
            'data-barangid' => $data->id,
            'class'         => 'tombol-setinaktif',
        ]);
    }

    public function actionSetInAktif($id)
    {
        $return = ['sukses' => false];
        if (isset($_POST['barangid'])) {
            $pk     = $_POST['barangid'];
            $barang = Barang::model()->findByPk($pk);
            Barang::model()->updateByPk($pk, ['status' => Barang::STATUS_TIDAK_AKTIF]);
            $return = ['sukses' => true];
        }
        $this->renderJSON($return);
    }

    public function actionSetNolAll($id)
    {
        $model = $this->loadModel($id);

        $this->renderJSON([
            'sukses' => true,
            'rows'   => $model->tambahDetailSetNol(),
        ]);
    }

    public function actionSetInAktifAll($id)
    {
        $model = $this->loadModel($id);

        $this->renderJSON([
            'sukses' => true,
            'rows'   => $model->setInAktifAll(),
        ]);
    }

    public function actionGantiInput($id)
    {
        $r = ['sukses' => false];

        $model = $this->loadModel($id);
        if (isset($_POST['gantiinput'])) {
            $model->input_selisih = !$model->input_selisih;

            if ($model->update(['input_selisih'])) {
                $r = [
                    'sukses'       => true,
                    'inputselisih' => $model->input_selisih,
                ];
            }
        }
        $this->renderJSON($r);
    }

    public function renderBarang($data, $row)
    {
        $text = $data->barang->barcode
            . '<br />'
            . $data->barang->nama;
        if (!is_null($data->ganti_rak_id)) {
            $rak = RakBarang::model()->findByPk($data->ganti_rak_id);
            $text .= '<br />'
                . '<i class="fa fa-level-up fa-rotate-90"></i> ' . $rak->nama;
        }

        return $text;
    }

    public function renderBarangExBar($data, $row)
    {
        $text = $data->barang->nama;
        if (!is_null($data->ganti_rak_id)) {
            $rak = RakBarang::model()->findByPk($data->ganti_rak_id);
            $text .= '<br />'
                . '<i class="fa fa-level-up fa-rotate-90"></i> ' . $rak->nama;
        }

        return $text;
    }

    public function renderFormInputManual($data, $row)
    {
        $this->renderPartial('_input_detail_manual_form', [
            'data'    => $data,
            'modelId' => Yii::app()->request->getParam('id'),
        ]);
    }
}
