<?php
include_once 'PosController.php';

class PosmController extends PosController
{
    public $layout = '//layouts/pos_mobile';
    public $SOStatus;
    public $SOId;
    public $SOTotal;
    public $showPembayaran = false;

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {
        $this->showPembayaran = true;
        $this->penjualanId    = $id;
        $model                = $this->loadModel($id);
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

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = (string) $_GET['barcodescan'];
        }

        $this->render(
            'ubah',
            [
                'model'                => $model,
                'penjualanDetail'      => $penjualanDetail,
                'barang'               => $barang,
                'tipeCari'             => $configCariBarang->nilai,
                'tarikTunaiBelanjaMin' => $configTarikTunaiMinBelanja->nilai,
                'scanBarcode'          => $scanBarcode,
                'poins'                => $poins,
            ]
        );
    }

    public function actionPesananUbah($id)
    {
        $this->layout = '//layouts/pos_mobile_pesanan';

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

        // Variabel untuk layout
        $this->SOId     = $model->id;
        $this->SOStatus = $model->status;
        $this->SOTotal  = $model->getTotal();

        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = (string) $_GET['barcodescan'];
        }
        $this->render(
            'pesanan_ubah',
            [
                'model'       => $model,
                'modelDetail' => $modelDetail,
                'barang'      => $barang,
                'tipeCari'    => $configCariBarang->nilai,
                'scanBarcode' => $scanBarcode,
            ]
        );
    }

    public function renderBarang($data, $row)
    {
        $diskon = $data->diskon > 0 ? ' (' . rtrim(rtrim(number_format($data->diskon, 2, ',', '.'), '0'), ',') . ')' : '';
        $text   = $data->barang->nama . ' (' . $data->barang->barcode . ')' .
            '<br />' .
            rtrim(rtrim(number_format($data->harga_jual + $data->diskon, 2, ',', '.'), '0'), ',') .
            $diskon .
            ' x ' . $data->qty . ' ' . $data->barang->satuan->nama .
            '<br />' .
            'Sub Total: ' . $data->total;

        return $text;
    }

    public function renderPesananColumn($data, $row, $dataColumn)
    {
        switch ($dataColumn->name) {
            case 'nomorTanggal':
                $nomor = empty($data->nomorF) ? '' : $data->nomorF . ' / ';
                return CHtml::link(
                    $nomor . $data->tanggal,
                    Yii::app()->controller->createUrl('pesananubah', ['id' => $data->id])
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

    public function renderBarangList($data, $row)
    {
        $text = $data->nama . ' (' . $data->barcode . ')' .
            '<br />' .
            $data->hargaJual;

        return $text;
    }

    public function actionCekHarga()
    {
        $urlCallback = $this->createAbsoluteUrl('cekharga');
        $scanBarcode = null;
        /* Ada scan dari aplikasi barcode scanner (android) */
        if (isset($_GET['barcodescan'])) {
            $scanBarcode = (string) $_GET['barcodescan'];
        }
        $this->render('cekharga', [
            'urlCallback' => $urlCallback,
            'scanBarcode' => $scanBarcode,
        ]);
    }
}
