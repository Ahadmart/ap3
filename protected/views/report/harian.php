<?php

/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Pembelian',
);

$this->boxHeader['small'] = 'Harian';
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan Harian';

$this->renderPartial('_form_harian', array('model' => $model));
