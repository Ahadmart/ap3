<?php
/* @var $this MembershipController */
/* @var $model Profil Member */

$this->breadcrumbs = [
    'Membership' => ['index'],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = 'Member: '.$model->nomor;

$this->renderPartial('_form_ubah', ['model' => $model]);
