<?php

class PosController extends Controller
{

    public $layout = '//layouts/pos_column3';
    public $namaProfil = null;
    public $profil = null;
    public $penjualanId = null;

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
     * Security tambahan, user yang bisa POS, adalah user dengan role kasir,
     * dan dalam keadaan kasir buka / aktif
     * @return boolean True jika kasir buka
     * @throws CHttpException Error jika kasir tutup
     */
    protected function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $kasirAktif = $this->posAktif();
            if (is_null($kasirAktif)) {
                throw new CHttpException(403, 'Akses ditolak: Kasir belum aktif');
            }
            return true;
        }
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $model = new Penjualan;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $model->profil_id = Profil::PROFIL_UMUM;

        if ($model->save())
            $this->redirect(array('ubah', 'id' => $model->id));

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
        $this->penjualanId = $id;
        $model = $this->loadModel($id);
        // Penjualan tidak bisa diubah kecuali statusnya draft
        if ($model->status != Penjualan::STATUS_DRAFT) {
            $this->redirect(array('index'));
        }

        $this->namaProfil = $model->profil->nama;
        $this->profil = Profil::model()->findByPk($model->profil_id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $penjualanDetail = new PenjualanDetail('search');
        $penjualanDetail->unsetAttributes();
        $penjualanDetail->setAttribute('penjualan_id', '=' . $id);

        $this->render('ubah', array(
            'model' => $model,
            'penjualanDetail' => $penjualanDetail
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
        if ($model->status == Penjualan::STATUS_DRAFT) {
            PenjualanDetail::model()->deleteAll('penjualan_id=:penjualanId', array('penjualanId' => $id));
            PenjualanDiskon::model()->deleteAll('penjualan_id=:penjualanId', array('penjualanId' => $id));
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
        $model = new Penjualan('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Penjualan']))
            $model->attributes = $_GET['Penjualan'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Memeriksa apakah current user dengan current IP, aktif
     * @return activeRecord Null if no record
     */
    public function posAktif()
    {
        return Kasir::model()
                        ->find('user_id=:userId and waktu_tutup is null', array(
                            ':userId' => Yii::app()->user->id
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Penjualan the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Penjualan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Penjualan $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'penjualan-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
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

    /**
     * Tambah barang jual
     * @param int $id ID Penjualan
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionTambahBarang($id)
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['barcode'])) {
            $penjualan = $this->loadModel($id);
            // Tambah barang hanya bisa jika status masih draft
            if ($penjualan->status == Penjualan::STATUS_DRAFT) {
                $barcode = $_POST['barcode'];
                $return = $penjualan->tambahBarang($barcode, 1);
            }
//            $barang = Barang::model()->find("barcode = '" . $barcode . "'");
//            $return['error']['msg'] = $penjualan->cekDiskon($barang->id);
        }
        $this->renderJSON($return);
    }

    public function actionKembalian()
    {
        echo ($_POST['bayar'] - $_POST['total']) < 0 ? '&nbsp' : number_format($_POST['bayar'] - $_POST['total'], 0, ',', '.');
    }

    public function renderQtyLinkEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="q"';
        }
        return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
                Yii::app()->controller->createUrl('updateqty') . '">' .
                $data->qty . '</a>';
    }

    public function renderHargaLinkEditable($data, $row)
    {
        $ak = '';
        if ($row == 0) {
            $ak = 'accesskey="t"';
        }
        return '<a href="#" class="editable-harga" data-type="text" data-pk="' . $data->id . '" ' . $ak . ' data-url="' .
                Yii::app()->controller->createUrl('updatehargamanual') . '">' .
                rtrim(rtrim(number_format($data->harga_jual, 2, ',', '.'), '0'), ',') . '</a>';
    }

    /**
     * Update qty detail pembelian via ajax
     */
    public function actionUpdateQty()
    {
        if (isset($_POST['pk'])) {
            $pk = $_POST['pk'];
            $qtyInput = $_POST['value'];
            $detail = PenjualanDetail::model()->findByPk($pk);
            if ($qtyInput > 0) {
                $selisih = $qtyInput - $detail->qty;

                $return = array('sukses' => false);
                $penjualan = $this->loadModel($detail->penjualan_id);
                $return = $penjualan->tambahBarang($detail->barang->barcode, $selisih);
            } else {
                PenjualanDiskon::model()->deleteAll('penjualan_detail_id=' . $pk);

                $detail->delete();
            }
            $return = array('sukses' => true);
            $this->renderJSON($return);
        }
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['barcode'])) {
            $penjualan = $this->loadModel($id);
            // Tambah barang hanya bisa jika status masih draft
            if ($penjualan->status == Penjualan::STATUS_DRAFT) {
                $barcode = $_POST['barcode'];
                $return = $penjualan->tambahBarang($barcode, 1);
            }
//            $barang = Barang::model()->find("barcode = '" . $barcode . "'");
//            $return['error']['msg'] = $penjualan->cekDiskon($barang->id);
        }
        $this->renderJSON($return);
    }

    public function actionSuspended()
    {
        $model = new Penjualan('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Penjualan'])) {
            $model->attributes = $_GET['Penjualan'];
        }
        $model->status = '=' . Penjualan::STATUS_DRAFT;
        $model->updated_by = '=' . Yii::app()->user->id;

        $this->render('suspended', array(
            'model' => $model,
        ));
    }

    /**
     * render link actionUbah
     * @param obj $data
     * @return string tanggal, beserta link
     */
    public function renderLinkToUbah($data)
    {
        $return = '<a href="' .
                $this->createUrl('ubah', array('id' => $data->id)) . '">' .
                $data->tanggal . '</a>';

        return $return;
    }

    public function actionSimpan($id)
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['pos'])) {
            $pos = Pos::model('Pos')->findByPk($id);
            $return = $pos->simpanPOS($_POST['pos']);
            if ($return['sukses']) {
                
            }
        }
        $this->renderJSON($return);
    }

    public function actionOut($id)
    {
        $kasir = $this->posAktif();
        $printId = $kasir->device->default_printer_id;
        if (!is_null($printId)) {
            $this->redirect(array('penjualan/printstruk', 'id' => $id, 'printId' => $printId));
        }
    }

    /**
     * Ganti Customer
     * @param int $id ID Penjualan
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionGantiCustomer($id)
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['nomor'])) {
            $customer = Profil::model()->find('nomor=:nomor', array(':nomor' => $_POST['nomor']));
            if (!is_null($customer)) {
                /* Simpan profil ID ke penjualan */
                $penjualan = $this->loadModel($id);
                if ($penjualan->saveAttributes(array('profil_id' => $customer->id))) {
                    $alamat1 = !empty($customer->alamat1) ? $customer->alamat1 : '';
                    $alamat2 = !empty($customer->alamat2) ? '<br>' . $customer->alamat2 : '';
                    $alamat3 = !empty($customer->alamat3) ? '<br>' . $customer->alamat3 : '';
                    $return = array(
                        'sukses' => true,
                        'nama' => $customer->nama,
                        'nomor' => $customer->nomor,
                        'address' => $alamat1 . $alamat2 . $alamat3
                    );
                }
            } else {
                $return = array(
                    'sukses' => false,
                    'error' => array(
                        'code' => '500',
                        'msg' => 'Data Customer tidak ditemukan',
                    )
                );
            }
        }
        $this->renderJSON($return);
    }

    public function actionAdminLogin($id)
    {
        $return = array(
            'sukses' => false,
            'error' => array(
                'code' => '500',
                'msg' => 'Sempurnakan input!',
            )
        );
        if (isset($_POST['usr'])) {
            $return = $this->authenticateAdmin($_POST['usr'], $_POST['pwd']);
        }
        $this->renderJSON($return);
    }

    /**
     * Mengecek user dan password, apakah punya hak admin
     * @param text $usr Nama user yang akan dicek
     * @param text $pwd Password
     * @return array status berhasil atau tidak
     */
    public function authenticateAdmin($usr, $pwd)
    {
        require_once __DIR__ . '/../vendors/password_compat/password.php';
        $user = User::model()->find('LOWER(nama)=?', array($usr));
        if ($user === null) {
            $return = array(
                'sukses' => false,
                'error' => array(
                    'code' => '500',
                    'msg' => 'Invalid User Name',
                )
            );
        } else if (!$user->validatePassword($pwd)) {
            $return = array(
                'sukses' => false,
                'error' => array(
                    'code' => '500',
                    'msg' => 'Invalid Password',
                )
            );
        } else if ($this->isAdmin($user)) {
            $return = array(
                'sukses' => true,
            );
            Yii::app()->user->setState('kasirOtorisasiAdmin', 1);
            Yii::app()->user->setState('kasirOtorisasiUserId', $user->id);
        }
        return $return;
    }

    public function isAdmin($user)
    {
        return Yii::app()->authManager->getAuthAssignment(Yii::app()->params['useradmin'], $user->id) === null ? FALSE : TRUE;
    }

    public function renderNamaBarang($data, $row)
    {
        $diskon = $data->diskon > 0 ? ' (' . rtrim(rtrim(number_format($data->diskon, 2, ',', '.'), '0'), ',') . ')' : '';
        $smallMediumText = $data->barang->nama .
                '<br />' .
                rtrim(rtrim(number_format($data->harga_jual + $data->diskon, 2, ',', '.'), '0'), ',') .
                $diskon .
                ' x ' . $data->qty . ' ' . $data->barang->satuan->nama;
        $largeUpText = $data->barang->nama;
        return '<span class="show-for-large-up">'.$largeUpText.'</span>'.
            '<span class="hide-for-large-up">'.$smallMediumText.'</span>';
    }

}
