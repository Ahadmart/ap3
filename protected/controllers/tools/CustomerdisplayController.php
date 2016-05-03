<?php

class CustomerdisplayController extends Controller
{

    public $layout = '//layouts/nonavbar';

    public function actionIndex()
    {
        $this->render('index');
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
        $this->renderPartial('_info', [ 'detailModel' => $this->getInfo()]);
    }

}
