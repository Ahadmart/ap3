<?php

/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

$this->breadcrumbs = [
    'Diskon Barang' => ['index'],
    'Tambah',
];

$this->boxHeader['small']  = 'Tambah';
$this->boxHeader['normal'] = 'Tambah Diskon Barang';

$this->renderPartial('_form', [
    'model'         => $model,
    'lv1'           => $lv1,
    'strukturDummy' => $strukturDummy,
    'listLevelMOL'  => $listLevelMOL,
]);

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions'       => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions'       => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
