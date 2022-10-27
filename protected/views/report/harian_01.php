<?php

/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Pembelian',
);

$this->boxHeader['small'] = $judul;
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan ' . $judul;

$this->renderPartial('_form_harian_01', [
    'model' => $model,
    'printers' => $printers,
    'kertasPdf' => $kertasPdf,
    'printHandle' => $printHandle
]);
