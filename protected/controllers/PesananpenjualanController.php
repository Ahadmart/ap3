<?php

class PesananpenjualanController extends Controller
{

    const PROFIL_ALL      = 0;
    const PROFIL_CUSTOMER = Profil::TIPE_CUSTOMER;

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {

        $modelDetail = new PesananPenjualanDetail('search');
        $modelDetail->unsetAttributes();
        $modelDetail->setAttribute('pesanan_penjualan_id', '=' . $id);

        $this->render('view',
                [
            'model'       => $this->loadModel($id),
            'modelDetail' => $modelDetail,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'ubah' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model        = new PesananPenjualan;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PesananPenjualan'])) {
            $model->attributes = $_POST['PesananPenjualan'];
            if ($model->save())
                $this->redirect(['ubah', 'id' => $model->id]);
        }

        $customerList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER,
            'order'     => 'nama']);

        $this->render('tambah', [
            'model'        => $model,
            'customerList' => $customerList,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $model = $this->loadModel($id);

        // Yang bisa diubah adalah yang statusnya DRAFT dan PESAN
        if ($model->status == PesananPenjualan::STATUS_JUAL || $model->status == PesananPenjualan::STATUS_BATAL) {
            $this->redirect(['view', 'id' => $id]);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['PesananPenjualan'])) {
            $model->attributes = $_POST['PesananPenjualan'];
            if ($model->save())
                $this->redirect(['view', 'id' => $id]);
        }

        $modelDetail = new PesananPenjualanDetail('search');
        $modelDetail->unsetAttributes();
        $modelDetail->setAttribute('pesanan_penjualan_id', '=' . $id);

        $barang = new Barang('search');
        $barang->unsetAttributes();

        if (isset($_GET['cariBarang'])) {
            $barang->setAttribute('nama', $_GET['namaBarang']);
            $barang->setAttribute('status', Barang::STATUS_AKTIF);
        }

        $this->render('ubah',
                [
            'model'       => $model,
            'modelDetail' => $modelDetail,
            'barang'      => $barang,
        ]);
    }

    /**
     * Deletes/Change Status a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionBatal($id)
    {
        $model = $this->loadModel($id);
        if ($model->status == PesananPenjualan::STATUS_DRAFT) {
            PesananPenjualanDetail::model()->deleteAll('pesanan_penjualan_id = :pesananId', [':pesananId' => $id]);
            $model->delete();
        }
        if ($model->status == PesananPenjualan::STATUS_PESAN) {
            $model->updateByPk($id, ['status' => PesananPenjualan::STATUS_BATAL]);
        }

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model             = new PesananPenjualan('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PesananPenjualan']))
            $model->attributes = $_GET['PesananPenjualan'];

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return PesananPenjualan the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = PesananPenjualan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PesananPenjualan $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'pesanan-penjualan-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * render nomor
     * @param obj $data
     * @return string nomor, beserta link yang sesuai
     */
    public function renderLinkNomor($data)
    {
        switch ($data->status) {
            case PesananPenjualan::STATUS_BATAL:
                //return $data->nomor;
                return '<a href="' . $this->createUrl('view', ['id' => $data->id]) . '">' . $data->nomor . '</a>';
                break;

            case PesananPenjualan::STATUS_JUAL:
                return '<a href="' . $this->createUrl('view', ['id' => $data->id]) . '">' . $data->nomor . '</a>';
                break;

            case PesananPenjualan::STATUS_PESAN:
                return '<a href="' . $this->createUrl('ubah', ['id' => $data->id]) . '">' . $data->nomor . '</a>';
                break;
        }
    }

    /**
     * render link actionUbah jika belum ada nomor
     * @param obj $data
     * @return string tanggal, beserta link jika masih draft (belum ada nomor)
     */
    public function renderLinkTanggalToUbah($data)
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
        $condition  = $tipe == Profil::TIPE_CUSTOMER ? 'id>' . Profil::AWAL_ID . ' and tipe_id=' . Profil::TIPE_CUSTOMER : 'id>' . Profil::AWAL_ID;
        $profilList = Profil::model()->findAll([
            'select'    => 'id, nama',
            'condition' => $condition,
            'order'     => 'nama']);
        /* FIX ME: Pindahkan ke view */
        $string     = '<option>Pilih satu..</option>';
        foreach ($profilList as $profil) {
            $string .= '<option value="' . $profil->id . '">';
            $string .= $profil->nama . '</option>';
        }
        echo $string;
    }

    /**
     * Tambah barang jual
     * @param int $id ID Pesanan
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionTambahDetail($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['tambah_barang']) && $_POST['tambah_barang']) {
            $pesanan = $this->loadModel($id);
            $qty     = $_POST['qty'];
            $barcode = $_POST['barcode'];
            $return  = $pesanan->tambahBarang($barcode, $qty);
        }
        $this->renderJSON($return);
    }

    public function actionHapusDetail($id)
    {
        $detail = PesananPenjualanDetail::model()->findByPk($id);
        if (!$detail->delete()) {
            throw new Exception('Gagal hapus detail penjualan');
        }
    }

    /**
     * Ambil total pesanan via ajax
     */
    public function actionTotal($id)
    {
        $pesanan          = $this->loadModel($id);
        $total            = $pesanan->ambilTotal();
        $totalF           = $pesanan->total;
        $return['sukses'] = true;
        $return['total']  = $total;
        $return['totalF'] = $totalF;
        $this->renderJSON($return);
    }

    public function actionPesan($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['pesan']) && $_POST['pesan']) {
            $pesanan = $this->loadModel($id);
            if ($pesanan->status == PesananPenjualan::STATUS_DRAFT) {
                $this->renderJSON($pesanan->simpan());
            }
        }
        $this->renderJSON($return);
    }

    /**
     * render status
     * @param obj $data
     * @return string link editable status
     */
    public function renderEditableStatus($data)
    {

        switch ($data->status) {
            case PesananPenjualan::STATUS_DRAFT:
                return $data->namaStatus;
                break;

            case PesananPenjualan::STATUS_PESAN:
                return $data->namaStatus;
                break;

            case PesananPenjualan::STATUS_JUAL:
                return '<a href="#" class="editable-status" data-type="select" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updatestatus') . '">' . $data->namaStatus . '</a>';
                break;

            case PesananPenjualan::STATUS_BATAL:
                return '<a href="#" class="editable-status" data-type="select" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updatestatus') . '">' . $data->namaStatus . '</a>';
                break;
        }
    }

    public function actionUpdateStatus()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ]
        ];
        if (isset($_POST['pk'])) {
            $pk     = $_POST['pk'];
            $status = $_POST['value'];
            if ($status == PesananPenjualan::STATUS_PESAN) {
                PesananPenjualan::model()->updateByPk($pk, ['status' => $status]);
                $return = ['sukses' => true];
            }
        }
        $this->renderJSON($return);
    }

}
