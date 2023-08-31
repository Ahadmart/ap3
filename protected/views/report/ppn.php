<?php

/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Pembelian',
);

$this->boxHeader['small'] = 'Laporan PPN';
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan PPN';

$this->renderPartial('_form_ppn', [
    'model' => $model,
]);
