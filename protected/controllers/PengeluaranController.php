<?php

class PengeluaranController extends Controller
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
        $detail = new PengeluaranDetail('search');
        $detail->unsetAttributes();
        if (isset($_GET['PengeluaranDetail'])) {
            $detail->attributes = $_GET['PengeluaranDetail'];
        }
        $detail->pengeluaran_id = '=' . $id;

        $this->render('view', array(
            'model' => $this->loadModel($id),
            'detail' => $detail
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $model = new Pengeluaran;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        /* Default value */
        $model->kas_bank_id = !empty($model->kas_bank_id) ? NULL : KasBank::model()->find('nama=:kas', [':kas' => 'Kas'])->id;
        $model->jenis_transaksi_id = !empty($model->jenis_transaksi_id) ? NULL : JenisTransaksi::model()->find('nama=:tunai', [':tunai' => 'Tunai'])->id;

        if (isset($_POST['Pengeluaran'])) {
            $model->attributes = $_POST['Pengeluaran'];
            if ($model->save())
                $this->redirect(array('ubah', 'id' => $model->id));
        }

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
        if (isset($_GET['Profil']))
            $profil->attributes = $_GET['Profil'];

        $this->render('tambah', array(
            'model' => $model,
            'profil' => $profil
        ));
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        // Jika pengeluaran sudah disimpan (status bukan draft) maka tidak bisa diubah lagi
        if ($model->status != Pengeluaran::STATUS_DRAFT) {
            $this->redirect(array('view', 'id' => $id));
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Pengeluaran'])) {
            $model->attributes = $_POST['Pengeluaran'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $id));
        }

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
        if (isset($_GET['Profil']))
            $profil->attributes = $_GET['Profil'];

        $itemKeuangan = new ItemKeuangan('search');
        $itemKeuangan->unsetAttributes();
        $itemKeuangan->scenario = 'hanyaDetail';
        if (isset($_GET['ItemKeuangan'])) {
            $itemKeuangan->attributes = $_GET['ItemKeuangan'];
            $itemKeuangan->aktif();
            //print_r($_GET['ItemKeuangan']);
        }
        $itemKeuangan->id = '>=' . ItemKeuangan::ITEM_TRX_SAJA;

        $hutangPiutang = new HutangPiutang('search');
        $hutangPiutang->unsetAttributes();
        if (isset($_GET['HutangPiutang'])) {
            $hutangPiutang->attributes = $_GET['HutangPiutang'];
        }
        $hutangPiutang->scenario = 'pilihDokumen';

        $pengeluaranDetail = new PengeluaranDetail;

        $detail = new PengeluaranDetail('search');
        $detail->unsetAttributes();
        if (isset($_GET['PengeluaranDetail'])) {
            $detail->attributes = $_GET['PengeluaranDetail'];
        }
        $detail->pengeluaran_id = '=' . $id;

        //$test = HutangPiutang::model()->findByPk(10);
        //echo $test->getNamaProfil();

        $this->render('ubah', array(
            'model' => $model,
            'profil' => $profil,
            'itemKeuangan' => $itemKeuangan,
            'hutangPiutang' => $hutangPiutang,
            'pengeluaranDetail' => $pengeluaranDetail,
            'detail' => $detail,
            'listNamaAsalHutangPiutang' => HutangPiutang::model()->listNamaAsal(),
            'listNamaTipe' => HutangPiutang::model()->listNamaTipe()
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == Pengeluaran::STATUS_DRAFT) {
            PengeluaranDetail::model()->deleteAll('pengeluaran_id=:id', [':id' => $id]);
            $model->delete();
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Pengeluaran('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Pengeluaran']))
            $model->attributes = $_GET['Pengeluaran'];

        $this->render('index', array(
            'model' => $model,
            'filterStatus' => Pengeluaran::model()->listFilterStatus(),
            'filterKasBank' => Pengeluaran::model()->listFilterKasBank(),
            'filterKategori' => Pengeluaran::model()->listFilterKategori(),
            'filterJenisTr' => Pengeluaran::model()->listFilterJenisTransaksi(),
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Pengeluaran the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Pengeluaran::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Pengeluaran $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pengeluaran-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionPilihProfil($id)
    {
        $profil = Profil::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'nama' => $profil->nama,
            'alamat1' => $profil->alamat1,
        );
        $this->renderJSON($return);
    }

    public function actionPilihItem($id)
    {
        $item = ItemKeuangan::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'namaParent' => $item->parent->nama,
            'nama' => $item->nama,
        );
        $this->renderJSON($return);
    }

    public function actionTambahDetail($id)
    {
        if (isset($_POST['PengeluaranDetail'])) {
            $detail = new PengeluaranDetail;
            $detail->attributes = $_POST['PengeluaranDetail'];
            $detail->pengeluaran_id = $id;
            $detail->save();
        }
    }

    public function actionHapusDetail($id)
    {
        PengeluaranDetail::model()->findByPk($id)->delete();
    }

    public function actionPilihDokumen($id)
    {
        $dokumen = HutangPiutang::model()->findByPk($id);
        $item = $dokumen->itemBayarHutang;
        $return = array(
            'id' => $id,
            'itemId' => $item['itemId'],
            'itemNama' => $item['itemNama'],
            'itemParent' => $item['itemParent'],
            'nomorDokumenAsal' => $dokumen->nomor_dokumen_asal,
            'nomor' => $dokumen->nomor,
            'jumlah' => $dokumen->sisa,
            'keterangan' => $dokumen->keterangan()
        );
        $this->renderJSON($return);
    }

    public function actionProses($id)
    {
        $return = array('sukses' => false);
        //	$this->renderJSON($_POST);
        if ($_POST['proses']) {
            $pengeluaran = $this->loadModel($id);
            if ($pengeluaran->status == Pengeluaran::STATUS_DRAFT) {
                if ($pengeluaran->proses()) {
                    $return = array('sukses' => true);
                }
            }
        }
        $this->renderJSON($return);
    }

    public function renderLinkView($data)
    {
        $return = '';
        if (isset($data->nomor)) {
            $return = '<a href="' .
                    Yii::app()->controller->createUrl('view', array('id' => $data->id)) . '">' .
                    $data->nomor . '</a>';
        }
        return $return;
    }

    public function renderLinkUbah($data)
    {
        if (!isset($data->nomor)) {
            $return = '<a href="' .
                    Yii::app()->controller->createUrl('ubah', array('id' => $data->id)) . '">' .
                    $data->tanggal . '</a>';
        } else {
            $return = $data->tanggal;
        }
        return $return;
    }

}
