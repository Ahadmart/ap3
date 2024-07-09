<?php
/* @var $this StockopnameController */
/* @var $model StockOpname */

$this->breadcrumbs = [
    'Stock Opname' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Stock Opname: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Rak</span><span class="label"><?php echo empty($model->rak) ? '-' : $model->rak->nama; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12  columns">
        <?php
        $this->widget('BGridView', [
            'id'                    => 'so-detail-grid',
            'dataProvider'          => $detail->search(),
            'filter'                => $detail,
            //'summaryText' => '{start}-{end} dari {count}, Total: ' . $model->total,
            'rowCssClassExpression' => function ($row, $data) {
                return $data->set_inaktif == 1 ? 'inaktif' : '';
            },
            'columns'               => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'barcode',
                    'header'    => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                    'value'     => '$data->barang->barcode',
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'namaBarang',
                    //   'value' => '$data->barang->nama',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderBarangExBar'],
                    'header'    => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                ],
                [
                    'name'              => 'qty_tercatat',
                    'filter'            => false,
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'name'              => 'qty_sebenarnya',
                    'filter'            => false,
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'header'            => 'Selisih',
                    'value'             => '$data->selisih',
                    'filter'            => false,
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
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
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>