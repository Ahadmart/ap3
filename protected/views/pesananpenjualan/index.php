<?php

/* @var $this PesananpenjualanController */
/* @var $model PesananPenjualan */

$this->breadcrumbs = [
    'Pesanan Penjualan' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Pesanan Penjualan';
$this->boxHeader['normal'] = 'Pesanan Penjualan';

$this->widget('BGridView',
        [
    'id'           => 'pesanan-penjualan-grid',
    'dataProvider' => $model->search(),
    'filter'       => $model,
    'columns'      => [
        [
            'class'     => 'BDataColumn',
            'name'      => 'nomor',
            'header'    => '<span class="ak">N</span>omor',
            'accesskey' => 'n',
            'type'      => 'raw',
            'value'     => [$this, 'renderLinkNomor']
        ],
        [
            'class'     => 'BDataColumn',
            'name'      => 'tanggal',
            'header'    => 'Tangga<span class="ak">l</span>',
            'accesskey' => 'l',
            'type'      => 'raw',
            'value'     => [$this, 'renderLinkTanggalToUbah']
        ],
        [
            'name'  => 'namaProfil',
            'value' => '$data->profil->nama'
        ],
        [
            'name'   => 'status',
            'value'  => '$data->namaStatus',
            'filter' => $model->listStatus()
        ],
        [
            'header'      => 'Total',
            'value'       => '$data->total',
            'htmlOptions' => ['class' => 'rata-kanan']
        ],
        [
            'name'  => 'namaUser',
            'value' => '$data->updatedBy->nama_lengkap',
        ],
        [
            'class'           => 'BButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("batal", array("id"=>$data->primaryKey))',
            'buttons'         => [
                'delete' => [
                    'visible' => '$data->status == ' . PesananPenjualan::STATUS_DRAFT . ' OR $data->status == ' . PesananPenjualan::STATUS_PESAN,
                ]
            ]
        ],
    ],
]);

$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    [
        'itemOption'     => ['class' => 'has-form hide-for-small-only'],
        'label'          => '',
        'items'          => [
            [
                'label'       => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah',
                'url'         => $this->createUrl('tambah'),
                'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 't'
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'],
        'label'          => '',
        'items'          => [
            [
                'label'       => '<i class="fa fa-plus"></i>',
                'url'         => $this->createUrl('tambah'),
                'linkOptions' => [
                    'class' => 'button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
