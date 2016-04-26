<?php

/* @var $this ReportController */

$this->breadcrumbs = array(
    'Laporan' => array('index'),
    'Poin Member',
);

$this->boxHeader['small'] = $judul;
$this->boxHeader['normal'] = '<i class="fa fa-file fa-lg"></i> Laporan '.$judul;

$this->renderPartial('_form_poin_member', array('model' => $model));
