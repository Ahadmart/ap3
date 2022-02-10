<?php

/* @var $this LaporanharianController */
/* @var $model LaporanHarian */

$this->breadcrumbs = [
    'Membership' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Membership';
$this->boxHeader['normal'] = 'Membership';

// $this->renderPartial('_form', array('model' => $model));

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items'    => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">R</span>egistrasi', 'url' => $this->createUrl('registrasi'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'r'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items'    => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('registrasi'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'r'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
