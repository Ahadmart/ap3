<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs = [
    'Pembelian Ppn' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Ppn Pembelian: ' . $model->pembelian->nomor;
?>
<div class="row">
    <div class="small-12 columns">
        <?php $this->widget('BDetailView', [
            'data'       => $model,
            'attributes' => [
                [
                    'name'  => 'pembelian.nomor',
                    'label' => 'Pembelian',
                    'type'  => 'nomorDokumen',
                ],
                'npwp:npwp',
                'no_faktur_pajak:ppnFaktur',
                'total_ppn_hitung:uang',
                'total_ppn_faktur:uang',
                [
                    'name' => 'namaStatus',
                ],
                'updated_at:tanggalWaktu',
                [
                    'name'  => 'updatedBy.nama',
                    'label' => 'Updated By'
                ],
                'created_at:tanggalWaktu',
            ],
        ]); ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
    ['itemOptions'          => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
];
?>