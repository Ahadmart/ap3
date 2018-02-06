<?php
/* @var $this PoController */
/* @var $model Po */

$this->breadcrumbs = [
    'Po' => ['index'],
    'Tambah',
];

$this->boxHeader['small']  = 'Tambah';
$this->boxHeader['normal'] = 'Tambah PO';

$this->renderPartial('_form', [
    'model'        => $model,
    'supplierList' => $supplierList,
]);

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions'   => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    ['itemOptions'   => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
