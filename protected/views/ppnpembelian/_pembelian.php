<?php
/* @var $this PembelianController */
/* @var $model Pembelian */

$this->breadcrumbs = [
    'Pembelian' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Pembelian';
$this->boxHeader['normal'] = 'Pembelian';

?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'pembelian-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'tabel-index',
            'columns'       => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nomor',
                    'header'    => '<span class="ak">N</span>omor',
                    'accesskey' => 'n',
                    'type'      => 'raw',
                    'value'     => function ($data) {
                        return '<a href="' . Yii::app()->controller->createUrl('pilihpembelian', ['id' => $data->id]) . '" class="pilih pembelian">' . $data->nomor . '</a>';
                    },
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'tanggal',
                    'header'    => 'Tangga<span class="ak">l</span>',
                    'accesskey' => 'l',
                    'type'      => 'raw',
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'namaSupplier',
                    'header'    => 'Pro<span class="ak">f</span>il',
                    'accesskey' => 'f',
                    'type'      => 'raw',
                    'value'     => '$data->profil->nama',
                    // 'value' => array($this, 'renderLinkToSupplier')
                ],
                'referensi',
                'tanggal_referensi',
                /*
        array(
        'name' => 'nomorHutang',
        'value' => 'is_null($data->hutangPiutang)?"":$data->hutangPiutang->nomor'
        ),
         */
                // [
                //     'name'   => 'status',
                //     'value'  => '$data->namaStatus',
                //     'filter' => ['0' => 'Draft', '1' => 'Hutang', '2' => 'Lunas'],
                // ],
                [
                    'header'            => 'Total',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                // [
                //     'name'   => 'hutangBayar',
                //     'type'   => 'raw',
                //     'filter' => false,
                //     'value'  => [$this, 'renderHutangBayar'],
                // ],
                [
                    'name'  => 'namaUpdatedBy',
                    'value' => '$data->updatedBy->nama_lengkap',
                ],
                // [
                //     'class'   => 'BButtonColumn',
                //     'buttons' => [
                //         'delete' => [
                //             'visible' => '$data->status == ' . Pembelian::STATUS_DRAFT,
                //         ],
                //     ],
                // ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items'          => [
            ['label' => '<i class="fa fa-download"></i> I<span class="ak">m</span>port', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class'     => 'warning button',
                'accesskey' => 'm',
            ]],
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items'          => [
            ['label' => '<i class="fa fa-download"></i>', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class' => 'warning button',
            ]],
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>