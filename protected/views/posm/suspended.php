<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = [
    'Penjualan' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Suspended';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Suspended';

?>
<script>
    $(function() {
        $("#tombol-new").focus();
    });
</script>
<div class="row collapse">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'penjualan-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'tabel-index',
            'template'      => '{items}{summary}{pager}',
            'columns'       => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'tanggal',
                    'header'    => 'Tang<span class="ak">g</span>al',
                    'accesskey' => 'g',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkToUbah']
                ],
                [
                    'name'  => 'namaProfil',
                    'value' => '$data->profil->nama'
                ],
                [
                    'header'            => 'Total',
                    'value'             => '$data->total',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan']
                ],
                /*
          array(
          'class' => 'BButtonColumn',
          ),
         */
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items'       => [
            ['label' => '<i class="fa fa-download"></i> I<span class="ak">m</span>port', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class'     => 'warning button',
                'accesskey' => 'm'
            ]],
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't'
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items'       => [
            ['label' => '<i class="fa fa-download"></i>', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class' => 'warning button',
            ]],
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
