<?php
/* @var $this MembershipController */
/* @var $model KodeAkun */

$this->breadcrumbs = [
    'Membership' => ['index'],
    'Registrasi',
];

$this->boxHeader['small']  = 'Registrasi';
$this->boxHeader['normal'] = 'Registrasi Member';

$this->renderPartial('_form_registrasi', ['model' => $model]);

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
