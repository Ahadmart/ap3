<?php

class CustomerdisplayController extends Controller
{

    public $layout = '//layouts/nonavbar';

    public function actionIndex()
    {
        $this->render('index', [ 'detailModel' => $this->getInfo()]);
    }

    public function getInfo()
    {

        $criteria = new CDbCriteria;
        $criteria->alias = 'detail';
        $criteria->join = 'JOIN penjualan pj on detail.penjualan_id = pj.id and pj.status=' . Penjualan::STATUS_DRAFT;
        $criteria->order = 'detail.id desc';
        $criteria->condition = 'detail.updated_by =' . Yii::app()->user->id;
        return PenjualanDetail::model()->find($criteria);
    }

    public function actionGetInfo()
    {
        /*
         * SELECT detail.barang_id, detail.qty,detail.harga_jual,diskon FROM penjualan_detail detail  
         * JOIN penjualan pj on detail.penjualan_
          -> id = pj.id AND pj.status = 0 order by detail.id DESC LIMIT 1

         */
        $criteria = new CDbCriteria;
        $criteria->alias('detail');
        $criteria->join('penjualan pj', 'detail.penjualan_id = pj.id and pj.status=' . Penjualan::STATUS_DRAFT);
        $criteria->order('detail.id desc');
        $criteria->where('updated_by =' . Yii::app()->user->id);
        $detail = PenjualanDetail::model()->find($criteria);
        print_r($detail);
    }

}
