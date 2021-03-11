<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = [
    'Retur Pembelian' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Retur Pembelian';
$this->boxHeader['normal'] = 'Retur Pembelian';

?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'retur-pembelian-grid',
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
                    'value'     => [$this, 'renderLinkToView'],
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'tanggal',
                    'header'    => 'Tangga<span class="ak">l</span>',
                    'accesskey' => 'l',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkToUbah'],
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'namaSupplier',
                    'header'    => '<span class="ak">S</span>upplier',
                    'accesskey' => 's',
                    'type'      => 'raw',
                    'value'     => '$data->profil->nama',
                    // 'value' => array($this, 'renderLinkToSupplier')
                ],
                'referensi',
                'tanggal_referensi',
                [
                    'name'   => 'status',
                    'value'  => '$data->namaStatus',
                    'filter' => [
                        ReturPembelian::STATUS_DRAFT   => 'Draft',
                        ReturPembelian::STATUS_POSTED  => 'Posted',
                        ReturPembelian::STATUS_PIUTANG => 'Piutang',
                        ReturPembelian::STATUS_LUNAS   => 'Lunas',
                        ReturPembelian::STATUS_BATAL   => 'Batal',
                    ],
                ],
                [
                    'header'            => 'Total',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'name'  => 'namaUpdatedBy',
                    'value' => '$data->updatedBy->nama_lengkap',
                ],
                [
                    'class'   => 'BButtonColumn',
                    'buttons' => [
                        'delete' => [
                            'visible' => '$data->status == ' . ReturPembelian::STATUS_DRAFT,
                        ],
                    ],
                ],
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
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
