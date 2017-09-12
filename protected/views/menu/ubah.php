<?php

/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs = [
    'Menu' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Menu: {$model->nama}";

$this->renderPartial('_form', [
    'model' => $model,
]);
$this->renderPartial('_form_detail', [
    'model' => $model,
    'model' => $subMenuModel,
]);

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                    'accesskey' => 't'
                ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];