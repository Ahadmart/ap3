<?php
/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = [
    'Barang' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Barang';
$this->boxHeader['normal'] = 'Barang';

//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

// Agar focus tetap di input cari barcode setelah pencarian
Yii::app()->clientScript->registerScript('editableQty', ''
    . '$( document ).ajaxComplete(function() {'
    . '$("input[name=\'Barang[barcode]\'").select();'
    . '});');
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'barang-grid',
            'dataProvider'  => $model->search(),
            'filter'        => $model,
            'itemsCssClass' => 'tabel-index',
            'columns'       => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'barcode',
                    'header'    => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                    'autoFocus' => true,
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nama',
                    'header'    => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkToView'],
                ],
                [
                    'name'   => 'satuan_id',
                    'value'  => '$data->satuan->nama',
                    'filter' => $model->filterSatuan(),
                ],
                [
                    'name'   => 'kategori_id',
                    'value'  => '$data->kategori == null ? "NULL" : $data->kategori->nama',
                    'filter' => $model->filterKategori(),
                ],
                [
                    'name'  => 'strukturFullPath',
                    'value' => '$data->namaStruktur',
                ],
                [
                    'name'   => 'rak_id',
                    'value'  => '$data->rak == null ? "NULL":$data->rak->nama',
                    'filter' => $model->filterRak(),
                ],
                [
                    'name'   => 'status',
                    'value'  => '$data->namaStatus',
                    'filter' => $model->filterStatus(),
                ],
                [
                    'header'            => 'Harga Jual',
                    'value'             => '$data->hargaJual',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                ],
                [
                    'name'              => 'Stok',
                    'value'             => '$data->stok',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'filter'            => false,
                ],
                [
                    'name'              => 'Stok Retur Beli',
                    'value'             => '$data->qtyReturBeliPosted',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'filter'            => false,
                    // 'visible'           => $showQtyReturBeli,
                ],
                /*
        array(
        'name' => 'restock_point',
        'htmlOptions' => array('class' => 'rata-kanan'),
        'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
        'filter' => false,
        'sortable' => false
        ),
        array(
        'name' => 'restock_level',
        'htmlOptions' => array('class' => 'rata-kanan'),
        'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
        'filter' => false,
        'sortable' => false
        ),
         */
                [
                    'class' => 'BButtonColumn',
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
