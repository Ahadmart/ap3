<?php

class CustomerdisplayController extends Controller
{
    public $layout = '//layouts/nonavbar';

    public function actionMobile()
    {
        $this->render('mobile');
    }

    public function getInfoScan()
    {
        $criteria            = new CDbCriteria;
        $criteria->alias     = 'detail';
        $criteria->join      = 'JOIN penjualan pj on detail.penjualan_id = pj.id and pj.status=' . Penjualan::STATUS_DRAFT;
        $criteria->order     = 'detail.id desc';
        $criteria->condition = 'detail.updated_by =' . Yii::app()->user->id . ' AND TIMESTAMPDIFF(MINUTE, detail.updated_at, NOW()) <= 2'; //detail.updated_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
        return PenjualanDetail::model()->find($criteria);
    }

    public function getInfoStruk()
    {
        return Penjualan::model()->find(['order' => 'id desc', 'condition' => 'status=' . Penjualan::STATUS_LUNAS . ' and TIMESTAMPDIFF(SECOND, tanggal, NOW()) <= 15']);
    }

    public function getInfoToko()
    {
        $config = Config::model()->find('nama=:namaToko', [':namaToko' => 'toko.nama']);
        return $config->nilai;
    }

    public function actionGetInfo()
    {
        if (!is_null($this->getInfoStruk())) {
            $this->renderPartial('_infostrukterakhir', ['penjualan' => $this->getInfoStruk()]);
        } elseif (!is_null($this->getInfoScan())) {
            $this->renderPartial('_infoscanterakhir', ['detailModel' => $this->getInfoScan()]);
        } else {
            $this->renderPartial('_kosong', ['namaToko' => $this->getInfoToko()]);
        }
    }

    public function actionDesktop()
    {
        $ws = [
            'ip'   => $_SERVER['SERVER_ADDR'],
            'port' => 48080,
        ];
        $user = [
            'id'          => Yii::app()->user->id,
            'namaLengkap' => Yii::app()->user->namaLengkap,
        ];
        $config   = Config::model()->find('nama=:nama', [':nama' => 'toko.nama']);
        $namaToko = $config->nilai;

        $this->render('desktop', [
            'namaToko' => $namaToko,
            'ws'       => $ws,
            'user'     => $user,
        ]);
    }
}
