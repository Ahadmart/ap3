<?php

class PosController extends Controller
{
    public $layout       = '//layouts/pos_column3';
    public $namaProfil   = null;
    public $profil       = null;
    public $penjualanId  = null;
    public $pesananId    = null;
    public $memberOnline = null;

    public $totalPenjualan = 0;
    public $showDiskonPerNota;
    public $showInfaq;
    public $showTarikTunai;
    public $showKoinMOL;
    public $showVoucherMOL;
    public $showMember;
    public $showMemberOL;

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
        /*
        Jika ada suspended sale (Status Draft, Profil = Umum, User ybs, dan belum ada detail) yang masih 0 (NOL)
        Maka ini dipakai terlebih dahulu
         */
        $suspendedSale = Penjualan::model()->find([
            'condition' => 't.status=:sDraft and t.profil_id=:pUmum and t.updated_by=:userId and penjualan_detail.id IS NULL',
            'order'     => 't.id',
            'join'      => 'LEFT JOIN penjualan_detail ON t.id=penjualan_detail.penjualan_id',
            'params'    => [
                ':sDraft' => Penjualan::STATUS_DRAFT,
                ':pUmum'  => Profil::PROFIL_UMUM,
                ':userId' => Yii::app()->user->id,
            ],
        ]);
        if (!is_null($suspendedSale)) {
            $this->redirect(['ubah', 'id' => $suspendedSale->id]);
        }

        $model = new Penjualan;

        $model->profil_id = Profil::PROFIL_UMUM;

        if ($model->save()) {
            $this->redirect(['ubah', 'id' => $model->id]);
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
        $this->penjualanId = $id;
        $model             = $this->loadModel($id);
        // Penjualan tidak bisa diubah kecuali statusnya draft
        if ($model->status != Penjualan::STATUS_DRAFT) {
            $this->redirect(['index']);
        }

        $this->namaProfil = $model->profil->nama;
        $this->profil     = Profil::model()->findByPk($model->profil_id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $penjualanDetail = new PenjualanDetail('search');
        $penjualanDetail->unsetAttributes();
        $penjualanDetail->setAttribute('penjualan_id', '=' . $id);

        $barang = new Barang('search');
        $barang->unsetAttributes();
        $barang->setAttribute('id', '0');

        if (isset($_GET['cariBarang'])) {
            $barang->unsetAttributes(['id']);
            $barang->setAttribute('nama', $_GET['namaBarang']);
            $criteria            = new CDbCriteria;
            $criteria->condition = 'status = :status';
            $criteria->order     = 'nama';
            $criteria->params    = [':status' => Barang::STATUS_AKTIF];
            $barang->setDbCriteria($criteria);
        }

        $configCariBarang           = Config::model()->find("nama='pos.caribarangmode'");
        $configTarikTunaiMinBelanja = Config::model()->find("nama='pos.tariktunaiminlimit'");

        $posModeAdmin = Yii::app()->user->getState('posModeAdminAlwaysON');
        if ($posModeAdmin) {
            Yii::app()->user->setState('kasirOtorisasiAdmin', $id);
            Yii::app()->user->setState('kasirOtorisasiUserId', Yii::app()->user->id);
        }

        $configShowDiskonNota = Config::model()->find("nama='pos.showdiskonpernota'");
        $configShowInfaq      = Config::model()->find("nama='pos.showinfak'");
        $configShowTarikTunai = Config::model()->find("nama='pos.showtariktunai'");
        $configShowMember     = Config::model()->find("nama='pos.showmember'");
        $configShowMemberOL   = Config::model()->find("nama='pos.showmembership'");

        $showDiskonPerNota = is_null($configShowDiskonNota) ? 0 : $configShowDiskonNota->nilai;
        $showInfaq         = is_null($configShowInfaq) ? 0 : $configShowInfaq->nilai;
        $showTarikTunai    = is_null($configShowTarikTunai) ? 0 : $configShowTarikTunai->nilai;
        $showMember        = is_null($configShowMember) ? 0 : $configShowMember->nilai;
        $showMemberOL      = is_null($configShowMemberOL) ? 0 : $configShowMemberOL->nilai;

        $memberOL = PenjualanMemberOnline::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $id]);
        $poins    = null;
        if (!is_null($memberOL)) {
            $clientAPI          = new AhadMembershipClient();
            $r                  = json_decode($clientAPI->view($memberOL->nomor_member));
            $this->memberOnline = $r->data->profil;

            $infoPoinMOL = json_decode($clientAPI->infoPoin());
            $poins       = $model->getCurPoinMOL($infoPoinMOL->data->satuPoin, $infoPoinMOL->data->satuKoin);

            if ($r->data->profil->koin > 0) {
                $this->showKoinMOL = true;
            }
        }
        $this->totalPenjualan    = $model->getTotal();
        $this->showDiskonPerNota = $showDiskonPerNota;
        $this->showInfaq         = $showInfaq;
        $this->showTarikTunai    = $showTarikTunai;
        $this->showMember        = $showMember;
        $this->showMemberOL      = $showMemberOL;

        $this->showVoucherMOL = true;

        $clientWS = new AhadPosWsClient();
        $data     = [
            'tipe'      => AhadPosWsClient::TIPE_PROCESS,
            'timestamp' => Date('Y-m-d H:i:s'),
            'u_id'      => Yii::app()->user->id,
            'profil'    => [
                'id'   => $model->profil_id,
                'nama' => $model->profil->nama,
                'mol'  => $this->memberOnline,
            ],
            'detail' => $model->getDetailArr(),
        ];

        $this->render(
            'ubah',
            [
                'model'                => $model,
                'penjualanDetail'      => $penjualanDetail,
                'barang'               => $barang,
                'tipeCari'             => $configCariBarang->nilai,
                'showDiskonPerNota'    => $showDiskonPerNota,
                'showInfaq'            => $showInfaq,
                'showTarikTunai'       => $showTarikTunai,
                'tarikTunaiBelanjaMin' => $configTarikTunaiMinBelanja->nilai,
                'poins'                => $poins,
            ]
        );
        $clientWS->sendMessage(json_encode($data));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        if ($this->isOtorisasiAdmin($id)) {
            $model = $this->loadModel($id);
            if ($model->status == Penjualan::STATUS_DRAFT) {
                PenjualanDiskon::model()->deleteAll('penjualan_id=:penjualanId', ['penjualanId' => $id]);
                PenjualanMultiHarga::model()->deleteAll('penjualan_id=:penjualanId', ['penjualanId' => $id]);
                $this->simpanHapus($id);
                PenjualanDetail::model()->deleteAll('penjualan_id=:penjualanId', ['penjualanId' => $id]);
                $model->delete();
            }
            $this->renderJSON(['sukses' => true]);
        } else {
            $this->renderJSON([
                'sukses' => false,
                'error'  => [
                    'code' => '501',
                    'msg'  => 'Harus dengan Otorisasi Admin',
                ],
            ]);
        }
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Penjualan('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Penjualan'])) {
            $model->attributes = $_GET['Penjualan'];
        }

        $this->render('//pos/index', [
            'model' => $model,
        ]);
    }

    /**
     * Memeriksa apakah current user dengan current IP, aktif
     * @return activeRecord Null if no record
     */
    public function posAktif()
    {
        return Kasir::model()
            ->find(
                'user_id=:userId and waktu_tutup is null',
                [
                    ':userId' => Yii::app()->user->id,
                ]
            );
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
        $model = Pos::model('Pos')->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

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

    /*
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
    $q->order = 'nama';
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
     */

    public function actionCariBarang($term)
    {
        $q = new CDbCriteria();
        $q->addCondition('(barcode like :term OR nama like :term) AND status = :status');
        $q->order  = 'nama';
        $q->params = [':term' => "%{$term}%", ':status' => Barang::STATUS_AKTIF];
        $barangs   = Barang::model()->findAll($q);

        $r = [];
        foreach ($barangs as $barang) {
            $r[] = [
                'label'  => $barang->nama,
                'value'  => $barang->barcode,
                'stok'   => is_null($barang->stok) ? 'null' : $barang->stok,
                'harga'  => $barang->hargaJual,
                'status' => $barang->status,
            ];
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
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['barcode'])) {
            $penjualan = $this->loadModel($id);
            // Tambah barang hanya bisa jika status masih draft
            if ($penjualan->status == Penjualan::STATUS_DRAFT) {
                $barcode = $_POST['barcode'];
                $return  = $penjualan->tambahBarang($barcode, 1);
            }
            //            $barang = Barang::model()->find("barcode = '" . $barcode . "'");
            //            $return['error']['msg'] = $penjualan->cekDiskon($barang->id);
        }
        $this->renderJSON($return);
    }

    public function actionKembalian()
    {
        /*
        echo ($_POST['bayar'] - $_POST['total'] + $_POST['diskonNota'] - $_POST['infaq']) < 0 ? '&nbsp' :
        number_format($_POST['bayar'] - $_POST['total'] + $_POST['diskonNota'] - $_POST['infaq'], 0, ',', '.');
         */
        $bayar      = empty($_POST['bayar']) ? 0 : $_POST['bayar'];
        $total      = empty($_POST['total']) || $_POST['total'] == 'NaN' ? 0 : $_POST['total'];
        $diskonNota = empty($_POST['diskonNota']) ? 0 : $_POST['diskonNota'];
        $koinMOL    = empty($_POST['koinMOL']) ? 0 : $_POST['koinMOL'];
        $infaq      = empty($_POST['infaq']) ? 0 : $_POST['infaq'];
        $tarikTunai = empty($_POST['tarikTunai']) ? 0 : $_POST['tarikTunai'];
        echo number_format($bayar - $total + $diskonNota + $koinMOL - $infaq - $tarikTunai, 0, ',', '.');
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
        if ($this->isOtorisasiAdmin($data->penjualan_id)) {
            /* Untuk user otorisasi admin, tampilkan harga editable */
            $ak = '';
            if ($row == 0) {
                $ak = 'accesskey="t"';
            }
            return CHtml::link(
                rtrim(rtrim(number_format($data->harga_jual, 2, ',', '.'), '0'), ','),
                '',
                [
                    'class'     => 'editable-harga',
                    'data-type' => 'text',
                    'data-pk'   => $data->id,
                    'data-url'  => Yii::app()->controller->createUrl('updatehargamanual'),
                    'accesskey' => $row == 0 ? 't' : '',
                ]
            );
        } else {
            /* Yang tidak, tampilkan text harga */
            return rtrim(rtrim(number_format($data->harga_jual, 2, ',', '.'), '0'), ',');
        }
    }

    /**
     * Update qty detail penjualan via ajax
     */
    public function actionUpdateQty()
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
            $detail   = PenjualanDetail::model()->findByPk($pk);
            if ($qtyInput > 0) {
                $selisih = $qtyInput - $detail->qty;

                $return    = ['sukses' => false];
                $penjualan = $this->loadModel($detail->penjualan_id);
                $return    = $penjualan->tambahBarang($detail->barang->barcode, $selisih);
            } else {
                /* qty=0 / hapus barang, hanya bisa jika ada otorisasi Admin */
                if ($this->isOtorisasiAdmin($detail->penjualan_id)) {
                    $barang    = Barang::model()->findByPk($detail->barang_id);
                    $penjualan = Penjualan::model()->findByPk($detail->penjualan_id);
                    $details   = PenjualanDetail::model()->findAll(
                        'barang_id=:barangId AND penjualan_id=:penjualanId',
                        [
                            ':barangId'    => $detail->barang_id,
                            ':penjualanId' => $detail->penjualan_id,
                        ]
                    );
                    foreach ($details as $d) {
                        $this->simpanHapusDetail($d); // Simpan barang yang dihapus ke tabel "lain"
                    }
                    $penjualan->cleanBarang($barang); // Bersihkan barang dari penjualan "ini"

                    $return = ['sukses' => true];
                } else {
                    // throw new Exception('Tidak ada otorisasi Admin', 401);
                    $return = [
                        'sukses' => false,
                        'error'  => [
                            'code' => '401',
                            'msg'  => 'Hapus detail harus dengan otorisasi Admin',
                        ],
                    ];
                }
            }
        }
        $this->renderJSON($return);
    }

    public function actionSuspended()
    {
        $model = new Penjualan('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Penjualan'])) {
            $model->attributes = $_GET['Penjualan'];
        }
        $model->status     = '=' . Penjualan::STATUS_DRAFT;
        $model->updated_by = '=' . Yii::app()->user->id;

        $clientWS = new AhadPosWsClient();
        $data     = [
            'tipe'      => AhadPosWsClient::TIPE_IDLE,
            'timestamp' => Date('Y-m-d H:i:s'),
            'u_id'      => Yii::app()->user->id,
        ];

        $this->render('suspended', [
            'model' => $model,
        ]);
        $clientWS->sendMessage(json_encode($data));
    }

    public function actionCekHarga()
    {
        $this->render('cekharga');
    }

    /**
     * render link actionUbah
     * @param obj $data
     * @return string tanggal, beserta link
     */
    public function renderLinkToUbah($data)
    {
        $return = '<a href="' .
            $this->createUrl('ubah', ['id' => $data->id]) . '">' .
            $data->tanggal . '</a>';

        return $return;
    }

    public function actionSimpan($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];

        if (isset($_POST['pos'])) {
            $pos = Pos::model('Pos')->findByPk($id);

            $i = 1;
            /* Simpan, jiga gagal dicoba max 3 kali */
            while ($pos->status == Penjualan::STATUS_DRAFT && $return['sukses'] == false && $i <= 3) {
                $return = $pos->simpanPOS($_POST['pos']);
                $i++;
            }

            if ($return['sukses']) {
                if ($this->isOtorisasiAdmin($id)) {
                    $this->adminLogout();
                }
            }
        }
        $this->renderJSON($return);
    }

    public function actionOut($id)
    {
        $kasir   = $this->posAktif();
        $printId = $kasir->device->default_printer_id;
        if (!is_null($printId)) {
            $this->redirect(['penjualan/printstruk', 'id' => $id, 'printId' => $printId]);
        }
    }

    /**
     * Ganti Customer
     * @param int $id ID Penjualan
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionGantiCustomer($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];

        if (isset($_POST['nomor'])) {
            if (trim($_POST['nomor']) == '') {
                /* Jika tidak diinput nomornya, maka set ke customer Umum */
                $customer = Profil::model()->findByPk(Profil::PROFIL_UMUM);
            } else {
                $customer = Profil::model()->find('nomor=:nomor', [':nomor' => $_POST['nomor']]);
            }
            if (!is_null($customer)) {
                $penjualan = $this->loadModel($id);

                /* Simpan profil ID ke penjualan
                 * dan sesuaikan diskon
                 */
                $return = $penjualan->gantiCustomer($customer);
            } else {
                $return = [
                    'sukses' => false,
                    'error'  => [
                        'code' => '404',
                        'msg'  => 'Data Customer tidak ditemukan',
                    ],
                ];
            }
        }
        $this->renderJSON($return);
    }

    /**
     * GantiMember function
     * Ganti Member Online by nomor member
     * @param int $id penjualan_id
     * @uses $_POST['nomor] nomor member online
     * @return string json profil member online atau error
     */
    public function actionGantiMember($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];

        if (isset($_POST['nomor']) && !empty($_POST['nomor'])) {
            $clientAPI = new AhadMembershipClient();
            $profil    = json_decode($clientAPI->view($_POST['nomor']), true);
            if ($profil['statusCode'] == 200) {
                // Ganti customer offline ke profil member online
                $customer  = Profil::model()->find('tipe_id=:tipeId', [':tipeId' => Profil::TIPE_MEMBER_ONLINE]);
                $penjualan = $this->loadModel($id);
                $penjualan->gantiCustomer($customer);
                // Buat atau ganti penjualan_member_online
                $penjualanMOL = PenjualanMemberOnline::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $id]);
                if (is_null($penjualanMOL)) {
                    $penjualanMOL = new PenjualanMemberOnline;
                }
                $penjualanMOL->nomor_member = $profil['data']['profil']['nomor'];
                $penjualanMOL->penjualan_id = $id;
                $penjualanMOL->koin_dipakai = 0;
                $penjualanMOL->poin         = 0;
                $penjualanMOL->koin         = 0;
                $penjualanMOL->level        = 0;
                $penjualanMOL->level_nama   = $profil['data']['profil']['levelNama'];
                $penjualanMOL->total_poin   = $profil['data']['profil']['poin'];
                $penjualanMOL->total_koin   = $profil['data']['profil']['koin'];
                if (!$penjualanMOL->save()) {
                    throw new Exception('Gagal simpan penjualan_member_online: ' . var_export($penjualanMOL->getErrors(), true));
                }
            }
            $this->renderJSON($profil);
        } else {
            // Ganti ke profil umum, hapus penjualan_member_online jika ada
            $customer  = Profil::model()->findByPk(Profil::PROFIL_UMUM);
            $penjualan = $this->loadModel($id);
            $penjualan->gantiCustomer($customer);
            $penjualanMOL = PenjualanMemberOnline::model()->find('penjualan_id=:penjualanId', [':penjualanId' => $id]);
            if (!is_null($penjualanMOL)) {
                $penjualanMOL->delete();
            }
            $return = [
                'statusCode' => 200,
                'data'       => [
                    'profil' => [
                        'nomor'       => '-',
                        'namaLengkap' => '-',
                        'alamat'      => '',
                    ],
                ],
            ];
        }
        $this->renderJSON($return);
    }

    public function actionAdminLogout()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Input Error!',
            ],
        ];
        if (isset($_POST['confirm']) && $_POST['confirm'] == '1') {
            $this->adminLogout();
            $return = [
                'sukses' => true,
            ];
        }
        $this->renderJSON($return);
    }

    public function adminLogout()
    {
        Yii::app()->user->setState('kasirOtorisasiAdmin', null);
        Yii::app()->user->setState('kasirOtorisasiUserId', null);
    }

    public function actionAdminLogin()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['usr'])) {
            $return = $this->authenticateAdmin($_POST['usr'], $_POST['pwd'], $_POST['id']);
        }
        $this->renderJSON($return);
    }

    /**
     * Mengecek user dan password, apakah punya hak admin
     * @param text $usr Nama user yang akan dicek
     * @param text $pwd Password
     * @param int $penjualanId ID Penjualan
     * @return array status berhasil atau tidak
     */
    public function authenticateAdmin($usr, $pwd, $penjualanId)
    {
        require_once __DIR__ . '/../vendors/password_compat/password.php';
        $user = User::model()->find('LOWER(nama)=?', [$usr]);
        if ($user === null) {
            return [
                'sukses' => false,
                'error'  => [
                    'code' => '500',
                    'msg'  => 'Invalid User Name',
                ],
            ];
        } elseif (!$user->validatePassword($pwd)) {
            return [
                'sukses' => false,
                'error'  => [
                    'code' => '500',
                    'msg'  => 'Invalid Password',
                ],
            ];
        } elseif ($this->isAdmin($user)) {
            Yii::app()->user->setState('kasirOtorisasiAdmin', $penjualanId);
            Yii::app()->user->setState('kasirOtorisasiUserId', $user->id);
            return [
                'sukses' => true,
            ];
        }
    }

    /**
     * Cek $user apakah punya hak admin
     * @param ActiveRecord $user
     * @return boolean
     */
    public function isAdmin($user)
    {
        return Yii::app()->authManager->getAuthAssignment(Yii::app()->params['useradmin'], $user->id) === null ? false : true;
    }

    public function isOtorisasiAdmin($penjualanId)
    {
        return Yii::app()->user->getState('kasirOtorisasiAdmin') == $penjualanId;
    }

    public function renderNamaBarang($data, $row)
    {
        $diskon          = $data->diskon > 0 ? ' (' . rtrim(rtrim(number_format($data->diskon, 2, ',', '.'), '0'), ',') . ')' : '';
        $smallMediumText = $data->barang->nama .
            '<br />' .
            rtrim(rtrim(number_format($data->harga_jual + $data->diskon, 2, ',', '.'), '0'), ',') .
            $diskon .
            ' x ' . $data->qty . ' ' . $data->barang->satuan->nama;
        $largeUpText = $data->barang->nama;
        return '<span class="show-for-large-up">' . $largeUpText . '</span>' .
            '<span class="hide-for-large-up">' . $smallMediumText . '</span>';
    }

    public function actionUpdateHargaManual()
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['pk'])) {
            $pk              = $_POST['pk'];
            $hargaManual     = $_POST['value'];
            $penjualanDetail = PenjualanDetail::model()->findByPk($pk);
            $penjualan       = Penjualan::model()->findByPk($penjualanDetail->penjualan_id);
            $return          = $penjualan->updateHargaManual($penjualanDetail, $hargaManual);
        }
        $this->renderJSON($return);
    }

    /**
     * Menyimpan barang yang dihapus ke tabel penjualan_detail_h
     * @param ActiveRecord $detail Penjualan Detail
     * @param int $jenis Jenis Hapus (per barang, atau per nota), default per barang
     */
    public function simpanHapusDetail($detail, $jenis = PenjualanDetailHapus::JENIS_PER_BARANG)
    {
        $userAdmin = User::model()->findByPk(Yii::app()->user->getState('kasirOtorisasiUserId'));

        $penjualanHapus                  = new PenjualanDetailHapus;
        $penjualanHapus->barang_id       = $detail->barang_id;
        $penjualanHapus->barang_barcode  = $detail->barang->barcode;
        $penjualanHapus->barang_nama     = $detail->barang->nama;
        $penjualanHapus->harga_beli      = InventoryBalance::model()->getHargaBeliAwal($detail->barang_id);
        $penjualanHapus->harga_jual      = $detail->harga_jual;
        $penjualanHapus->user_kasir_id   = $detail->updated_by;
        $penjualanHapus->user_kasir_nama = $detail->updatedBy->nama;
        $penjualanHapus->user_admin_id   = $userAdmin->id;
        $penjualanHapus->user_admin_nama = $userAdmin->nama;
        $penjualanHapus->penjualan_id    = $detail->penjualan_id;
        $penjualanHapus->jenis           = $jenis;
        $penjualanHapus->save();
    }

    /**
     * Menyimpan semua detail yang ada di penjualan yang akan dihapus
     * @param int $penjualanId ID Penjualan
     */
    public function simpanHapus($penjualanId)
    {
        $details = PenjualanDetail::model()->findAll('penjualan_id = :penjualanId', [':penjualanId' => $penjualanId]);
        foreach ($details as $detail) {
            $this->simpanHapusDetail($detail, PenjualanDetailHapus::JENIS_PER_NOTA);
        }
    }

    /**
     * Input data dari AKM (Anjungan Kasir Mandiri)
     * @param int $id Penjualan ID
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionInputAkm($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['nomor']) && trim($_POST['nomor']) != '') {
            $nomor  = trim($_POST['nomor']);
            $model  = $this->loadModel($id);
            $return = $model->inputAkm($nomor);
        }
        $this->renderJSON($return);
    }

    public function actionPesanan()
    {
        $model = new So('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['So'])) {
            $model->attributes = $_GET['So'];
        }

        $criteria            = new CDbCriteria;
        $criteria->condition = 'status = :sDraft OR status = :sPesan';
        $criteria->params    = [
            ':sDraft' => So::STATUS_DRAFT,
            ':sPesan' => So::STATUS_PESAN,
        ];
        $model->setDbCriteria($criteria);

        $this->render('pesanan', ['model' => $model]);
    }

    public function renderPesananColumn($data, $row, $dataColumn)
    {
        switch ($dataColumn->name) {
            case 'nomorTanggal':
                $nomor = empty($data->nomorF) ? '' : $data->nomorF . ' / ';
                return CHtml::link(
                    $nomor . $data->tanggal,
                    Yii::app()->controller->createUrl('pos/pesananubah', ['id' => $data->id])
                );
                break;
            case 'tombolJual':
                if ($data->status == So::STATUS_PESAN) {
                    return CHtml::link(
                        '<i class="fa fa-shopping-cart fa-fw"></i>',
                        $this->createUrl('pesanansimpan', ['id' => $data->id]),
                        ['class' => 'link-jual']
                    );
                } else {
                    return '';
                }

                break;
        }
    }

    public function actionPesananUbah($id)
    {
        $this->layout = '//layouts/pos_column3_pesanan';

        $this->pesananId = $id;
        $model           = So::model()->findByPk($id);
        // Penjualan tidak bisa diubah kecuali statusnya draft atau pesan
        if ($model->status != So::STATUS_DRAFT and $model->status != So::STATUS_PESAN) {
            $this->redirect(['index']);
        }

        $this->namaProfil = $model->profil->nama;
        $this->profil     = Profil::model()->findByPk($model->profil_id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        $modelDetail = new SoDetail('search');
        $modelDetail->unsetAttributes();
        $modelDetail->setAttribute('so_id', '=' . $id);

        $barang = new Barang('search');
        $barang->unsetAttributes();
        $barang->setAttribute('id', '0');

        if (isset($_GET['cariBarang'])) {
            $barang->unsetAttributes(['id']);
            $barang->setAttribute('nama', $_GET['namaBarang']);
            $criteria            = new CDbCriteria;
            $criteria->condition = 'status = :status';
            $criteria->order     = 'nama';
            $criteria->params    = [':status' => Barang::STATUS_AKTIF];
            $barang->setDbCriteria($criteria);
        }

        $configCariBarang = Config::model()->find("nama='pos.caribarangmode'");

        $this->render(
            'pesanan_ubah',
            [
                'model'       => $model,
                'modelDetail' => $modelDetail,
                'barang'      => $barang,
                'tipeCari'    => $configCariBarang->nilai,
            ]
        );
    }

    /**
     * Tambah barang pesan
     * @param int $id ID Pesanan
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionPesananTambahBarang($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['barcode'])) {
            $pesanan = So::model()->findByPk($id);
            /* Tambah barang hanya bisa jika status masih draft atau pesan */
            if ($pesanan->status == So::STATUS_DRAFT || $pesanan->status == So::STATUS_PESAN) {
                $barcode = $_POST['barcode'];
                $return  = $pesanan->tambahBarang($barcode, 1);
            }
        }
        $this->renderJSON($return);
    }

    /**
     * Render Kolom Pesanan Detail
     * @param type $data
     * @param type $row
     * @return type
     */
    public function renderPesananDetailColumn($data, $row, $dataColumn)
    {
        switch ($dataColumn->name) {
            case 'harga_jual':
                return rtrim(rtrim(number_format($data->harga_jual, 2, ',', '.'), '0'), ',');
                break;

            case 'qty':
                $ak = [];
                if ($row == 0) {
                    $ak = ['accesskey' => 'q'];
                }
                return CHtml::link(
                    $data->qty,
                    '#',
                    array_merge(
                        $ak,
                        [
                            'class'     => 'editable-qty',
                            'data-type' => 'text',
                            'data-pk'   => $data->id,
                            'data-url'  => Yii::app()->controller->createUrl('pesananupdateqty'),
                        ]
                    )
                );
                break;
        }
    }

    /**
     * Update qty detail pesanan via ajax
     */
    public function actionPesananUpdateQty()
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
            $detail   = SoDetail::model()->findByPk($pk);
            if ($qtyInput > 0) {
                $selisih = $qtyInput - $detail->qty;

                $return  = ['sukses' => false];
                $pesanan = So::model()->findByPk($detail->so_id);
                $return  = $pesanan->tambahBarang($detail->barang->barcode, $selisih);
            } else {
                /* qty=0 / hapus barang */
                $barang  = Barang::model()->findByPk($detail->barang_id);
                $pesanan = So::model()->findByPk($detail->so_id);
                $details = SoDetail::model()->findAll(
                    'barang_id=:barangId AND so_id=:pesananID',
                    [
                        ':barangId'  => $detail->barang_id,
                        ':pesananID' => $detail->so_id,
                    ]
                );
                $pesanan->cleanBarang($barang); // Bersihkan barang dari penjualan "ini"
                $return = ['sukses' => true];
            }
        }
        $this->renderJSON($return);
    }

    public function actionPesananBaru()
    {
        $pesanan            = new So;
        $pesanan->profil_id = Profil::PROFIL_UMUM;
        if ($pesanan->save()) {
            $this->redirect(['pesananubah', 'id' => $pesanan->id]);
        } else {
            throw new CHttpException('Gagal simpan pesanan!');
        }
    }

    public function actionPesananSimpan($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['pesan']) && $_POST['pesan']) {
            $pesanan = So::model()->findByPk($id);
            if ($pesanan->status == So::STATUS_DRAFT && $pesanan->ambilTotal() > 0) {
                $this->renderJSON($pesanan->simpan());
            }
            if ($pesanan->status == So::STATUS_PESAN) {
                $penjualan            = new Pos;
                $penjualan->profil_id = $pesanan->profil_id;
                if ($penjualan->save()) {
                    $this->renderJSON(array_merge($penjualan->inputPesanan($id), ['penjualanId' => $penjualan->id]));
                }
            }
        }
        $this->renderJSON($return);
    }

    /**
     * Input data dari nomor Pesanan
     * @param int $id Penjualan ID
     * @return JSON boolean sukses, array error[code, msg]
     */
    public function actionInputPesanan($id)
    {
        $return = [
            'sukses' => false,
            'error'  => [
                'code' => '500',
                'msg'  => 'Sempurnakan input!',
            ],
        ];
        if (isset($_POST['nomor']) && trim($_POST['nomor']) != '') {
            $nomor  = trim($_POST['nomor']);
            $model  = $this->loadModel($id);
            $return = $model->inputPesananByNomor($nomor);
        }
        $this->renderJSON($return);
    }

    public function actionPesananPrint($id)
    {
        $kasir   = $this->posAktif();
        $printId = $kasir->device->default_printer_id;
        if (is_null($printId)) {
            throw new CHttpException('Tidak ada default Printer!');
        }
        $device  = Device::model()->findByPk($printId);
        $pesanan = So::model()->findByPk($id);
        $text    = $pesanan->strukText();

        $device->cashdrawer_kick = 0;
        if ($device->tipe_id == Device::TIPE_LPR) {
            $device->cashdrawer_kick = 0;
            $device->printLpr($pesanan->strukTextLPR());
            $this->redirect(['pesananubah', 'id' => $id]);
        } elseif ($device->tipe_id == Device::TIPE_TEXT_PRINTER) {
            $nomor    = $pesanan->nomor;
            $namaFile = "pesanan-{$nomor}";
            header('Content-type: text/plain');
            header("Content-Disposition: attachment; filename=\"{$namaFile}.text\"");
            header('Pragma: no-cache');
            header('Expire: 0');
            echo $device->revisiText($text);
            Yii::app()->end();
        } elseif ($device->tipe_id == Device::TIPE_BROWSER_PRINTER) {
            $this->renderPartial('//penjualan/_print_autoclose_browser', ['text' => $text]);
        }
    }
}
