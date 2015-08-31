<?php

/* @var $this LaporanharianController */
/* @var $model LaporanHarian */

$this->breadcrumbs = array(
    'Laporan Harian' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Laporan Harian';
$this->boxHeader['normal'] = 'Laporan Harian';

$this->renderPartial('_form', array('model' => $model));
