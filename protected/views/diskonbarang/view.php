<?php
/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

$this->breadcrumbs = [
    'Diskon Barang' => ['index'],
    $model->id,
];

$this->boxHeader['small'] = 'View';
$headerBoxSmall           = 'Diskon Barang: ';
if (!is_null($model->barang_id)) {
    $headerBoxSmall .= $model->barang->nama;
} else {
    $headerBoxSmall .= 'Semua Barang';
}
$this->boxHeader['normal'] = $headerBoxSmall;
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BDetailView', [
            'data'       => $model,
            'attributes' => [
                [
                    'label' => 'Tipe',
                    'value' => $model->getNamaTipe(),
                ],
                [
                    'name'  => 'barang.nama',
                    'label' => 'Nama',
                ],
                [
                    'name'  => 'barang.barcode',
                    'label' => 'Barcode',
                ],
                [
                    'name'  => 'barangKategori.nama',
                    'label' => 'Kategori Barang',
                ],
                [
                    'name'  => 'strukturFullPath',
                    'label' => 'Struktur Barang',
                ],
                [
                    'name'  => 'barang.hargaJual',
                    'label' => 'Harga Jual Asli',
                ],
                [
                    'name'  => 'nominal',
                    'value' => number_format($model->nominal, 0, ',', '.'),
                ],
                [
                    'name'  => 'persen',
                    'value' => number_format($model->persen, 2, ',', '.'),
                ],
                'dari',
                'sampai',
                'qty',
                'qty_min',
                'qty_max',
                [
                    'name'  => 'barangBonus.nama',
                    'label' => 'Barang Bonus',
                ],
                [
                    'name'  => 'barangBonus.barcode',
                    'label' => 'Barcode Barang Bonus',
                ],
                [
                    'name'  => 'barang_bonus_diskon_nominal',
                    'value' => number_format($model->barang_bonus_diskon_nominal, 0, ',', '.'),
                ],
                [
                    'name'  => 'barang_bonus_diskon_persen',
                    'value' => number_format($model->barang_bonus_diskon_persen, 2, ',', '.'),
                ],
                [
                    'label' => 'Status',
                    'value' => $model->namaStatus,
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
    [
        'itemOptions'          => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
];
