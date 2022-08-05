<?php

class BarangController extends Controller
{
    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);
        //$supplierBarang = SupplierBarang::model()->findAll('barang_id=' . $id);

        $supplierBarang = new SupplierBarang('search');
        $supplierBarang->unsetAttributes();
        $supplierBarang->setAttribute('barang_id', '=' . $id);

        $inventoryBalance = new InventoryBalance('search');
        $inventoryBalance->unsetAttributes();
        $inventoryBalance->setAttribute('barang_id', '=' . $id);
        //$inventoryBalance->setAttribute('qty', '<>0');
        $inventoryBalance->scenario = 'tampil';

        $hargaJual = new HargaJual('search');
        $hargaJual->unsetAttributes();
        $hargaJual->setAttribute('barang_id', '=' . $id);

        // $rrp = new HargaJualRekomendasi('search');
        // $rrp->unsetAttributes();
        // $rrp->setAttribute('barang_id', '=' . $id);

        $hjMultiList = HargaJualMulti::listAktif($id);

        $currentTags = $model->tagList;
        $curTags     = [];
        foreach ($currentTags as $curTag) {
            //print_r($curTag->tag->id);
            $curTags[] = $curTag->tag->nama;
        }

        $this->render('view', [
            'model'            => $model,
            'supplierBarang'   => $supplierBarang,
            'inventoryBalance' => $inventoryBalance,
            'hargaJual'        => $hargaJual,
            // 'rrp' => $rrp,
            'hjMultiList'    => $hjMultiList,
            'curTags'        => $curTags
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new Barang;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Barang'])) {
            $model->attributes = $_POST['Barang'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }
        $lv1 = new StrukturBarang('search');
        $lv1->unsetAttributes();  // clear any default values
        $lv1->setAttribute('level', 1); // default yang tampil
        $lv1->setAttribute('status', StrukturBarang::STATUS_PUBLISH);

        $strukturDummy = new StrukturBarang('search');
        $strukturDummy->unsetAttributes();  // clear any default values
        $strukturDummy->setAttribute('level', 0);

        $this->render('tambah', [
            'model' => $model,
            'lv1' => $lv1,
            'strukturDummy' => $strukturDummy
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

        $lv1 = new StrukturBarang('search');
        $lv1->unsetAttributes();  // clear any default values
        $lv1->setAttribute('level', 1); // default yang tampil
        $lv1->setAttribute('status', StrukturBarang::STATUS_PUBLISH);

        $strukturDummy = new StrukturBarang('search');
        $strukturDummy->unsetAttributes();  // clear any default values
        $strukturDummy->setAttribute('level', 0);

        $supplierBarang = new SupplierBarang('search');
        $supplierBarang->unsetAttributes();
        $supplierBarang->setAttribute('barang_id', '=' . $id);

        $hargaJual = new HargaJual('search');
        $hargaJual->unsetAttributes();
        $hargaJual->setAttribute('barang_id', '=' . $id);

        // $rrp = new HargaJualRekomendasi('search');
        // $rrp->unsetAttributes();
        // $rrp->setAttribute('barang_id', '=' . $id);

        $hargaJualMulti = new HargaJualMulti('search');
        $hargaJualMulti->unsetAttributes();
        $hargaJualMulti->setAttribute('barang_id', $id);

        $hjMultiModel = new HargaJualMulti;

        $hjMultiList = HargaJualMulti::listAktif($id);

        if (isset($_POST['Barang'])) {
            $model->attributes = $_POST['Barang'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $id]);
            }
        }

        $currentTags = $model->tagList;
        $curTags     = [];
        foreach ($currentTags as $curTag) {
            //print_r($curTag->tag->id);
            $curTags[] = $curTag->tag_id;
        }

        $this->render('ubah', [
            'model'             => $model,
            'lv1'               => $lv1,
            'strukturDummy'     => $strukturDummy,
            'supplierBarang'    => $supplierBarang,
            'listBukanSupplier' => $this->_listBukanSupplier($id),
            'hargaJual'         => $hargaJual,
            // 'rrp' => $rrp,
            'curTags'      => $curTags,
            'hjMultiModel' => $hjMultiModel,
            'hjMulti'      => $hargaJualMulti,
            'hjMultiList'  => $hjMultiList,
        ]);
    }

    public function actionTambahSupplier($id)
    {
        if (isset($_POST['supplier_id'])) {
            $supplierId         = $_POST['supplier_id'];
            $model              = new SupplierBarang;
            $model->barang_id   = $id;
            $model->supplier_id = $supplierId;
            if ($model->save()) {
                $model->assignDefaultSupplier($model->id, $id); // $id adalah barangId 
                echo 'berhasil';
            } else {
                echo 'tidak berhasil';
            }
        }
    }

    public function actionListBukanSupplier($id)
    {
        $this->renderPartial('_supplier_opt', [
            'listBukanSupplier' => $this->_listBukanSupplier($id)
        ]);
    }

    public function _listBukanSupplier($id)
    {
        return Profil::model()->listSupplierYangBukan($id);
    }

    public function actionAssignDefaultSup($id, $barangId)
    {
        SupplierBarang::model()->assignDefaultSupplier($id, $barangId);
    }

    public function actionRemoveSupplier($id)
    {
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'supplier-barang-grid') {
            $model    = SupplierBarang::model()->findByPk($id);
            $barangId = $model->barang_id;
            $model->delete();
            echo "model deleted; ";

            // Jika belum ada supplier default
            if (SupplierBarang::belumAdaSupDefault($barangId)) {
                echo "belum ada sup default; ";
                // Set default supplier terakhir
                $maxSB = SupplierBarang::ambilSupplierTerakhir($barangId);
                if ($maxSB > 0) {
                    echo "set default ke id: " . $maxSB;
                    SupplierBarang::model()->assignDefaultSupplier($maxSB, $barangId);
                }
            }
        }
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
        $model = new Barang('search');
        $model->unsetAttributes();  // clear any default values
        $model->setAttribute('status', Barang::STATUS_AKTIF); // default yang tampil
        if (isset($_GET['Barang'])) {
            $model->attributes = $_GET['Barang'];
        }

        // $configShowQtyReturBeli = Config::model()->find("nama='barang.showstokreturbeli'");
        // $showRB = $configShowQtyReturBeli->nilai == 1 ? true : false;
        $this->render('index', [
            'model' => $model,
            // 'showQtyReturBeli' => $showRB,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param  integer        $id the ID of the model to be loaded
     * @return Barang         the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Barang::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Barang $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'barang-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionUpdateHargaJual($id)
    {
        $hargaJual = $_POST['hj'];
        if (HargaJual::model()->updateHargaJualTrx($id, $hargaJual)) {
            echo 'Sukses';
        } else {
            echo 'Fail';
        }
    }

    public function actionUpdateRrp($id)
    {
        $hargaJual = $_POST['rrp'];
        if (HargaJualRekomendasi::model()->updateHargaJualTrx($id, $hargaJual)) {
            echo 'Sukses';
        } else {
            echo 'Fail';
        }
    }

    public function renderInventoryDocumentLinkToView($data)
    {
        $inventoryBalance = InventoryBalance::model()->findByPk($data->id);
        $namaController   = $inventoryBalance->namaAsalController();
        $model            = $inventoryBalance->modelAsal();
        return '<a href="' .
            $this->createUrl("{$namaController}/view", ['id' => $model->id]) . '">' .
            $data->nomor_dokumen . '</a>';
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

    public function actionUpdateTags($id)
    {
        $tags = $_POST['tags'];
        TagBarang::model()->updateTags($id, $tags);
        //print_r(TagBarang::model()->findAll('barang_id=:barangId',[':barangId'=>$id]));
    }

    public function actionUpdateHargaJualMulti($id)
    {
        $barangId   = $id;
        $attributes = $_POST['HargaJualMulti'];
        if (HargaJualMulti::updateHargaTrx($barangId, $attributes)) {
            echo 'Sukses';
        } else {
            echo 'Fail';
        }
    }

    public function actionListHargaJualMulti($id)
    {
        $hjMultiList = HargaJualMulti::listAktif($id);
        $this->renderPartial('_harga_jual_multi_aktif', ['hjMultiList' => $hjMultiList]);
        Yii::app()->end();
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
                $model->unsetAttributes();  // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid2', ['lv2' => $model]);
                break;
            case 3:
                $model = new StrukturBarang('search');
                $model->unsetAttributes();  // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid3', ['lv3' => $model]);
                break;
        }
    }

    public function actionUpdateStruktur($id)
    {
        $r = [
            'sukses' => false,
            'msg'    => "Struktur Level 3 belum dipilih"
        ];
        if (Yii::app()->request->getPost('struktur-id')) {
            $model              = $this->loadModel($id);
            $model->struktur_id = Yii::app()->request->getPost('struktur-id');
            if ($model->save()) {
                $r = [
                    'sukses'       => true,
                    'namastruktur' => $model->getNamaStruktur()
                ];
            } else {
                $r = [
                    'sukses' => false,
                    'msg'    => "Gagal update Struktur!"
                ];
            }
        }
        $this->renderJSON($r);
    }
}
