<?php
/* @var $this EditbarangController */
/* @var $model Barang */

$this->breadcrumbs = [
    'Barang' => ['index'],
    'Index',
];

$this->boxHeader['small'] = 'Edit Barang';
$this->boxHeader['normal'] = 'Edit Barang';

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
        <?= CHtml::link('Set Non Aktif', '#', ['class' => 'button', 'id' => 'tombol-set-na', 'disabled' => true]) ?>
    </div>
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id' => 'barang-grid',
            'dataProvider' => $model->search(50),
            'filter' => $model,
            'itemsCssClass' => 'tabel-index',
            'columns' => [
                [
                    'id' => 'kolomcek',
                    'class' => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                    'value' => '$data->id'
                ],
                [
                    'class' => 'BDataColumn',
                    'name' => 'barcode',
                    'header' => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                    'autoFocus' => true
                ],
                [
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                ],
                [
                    'name' => 'satuan_id',
                    'value' => '$data->satuan->nama',
                    'filter' => $model->filterSatuan()
                ],
                [
                    'name' => 'kategori_id',
                    'value' => '$data->kategori->nama',
                    'filter' => $model->filterKategori()
                ],
                [
                    'name' => 'rak_id',
                    'value' => '$data->rak == null ? "NULL":$data->rak->nama',
                    'filter' => $model->filterRak()
                ],
                [
                    'name' => 'status',
                    'value' => '$data->namaStatus',
                    'filter' => $model->filterStatus()
                ],
                [
                    'header' => 'Harga Jual',
                    'value' => '$data->hargaJual',
                    'htmlOptions' => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                ],
                [
                    'name' => 'Stok',
                    'value' => '$data->stok',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false
                ],
                [
                    'name' => 'Pembelian Terakhir',
                    'value' => '$data->tanggalBeliTerakhir',
                    //'htmlOptions' => array('class' => 'rata-kanan'),
                    //'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false
                ],
            ],
        ]);
        ?>
    </div>
</div>
<script>
    $("#tombol-set-na").click(function () {
        if ($(this).is("[disabled]")) {
            console.log('disabled clicked');
        } else {
            alert($('#barang-grid').yiiGridView('getChecked', 'kolomcek'));
        }
    });
    $(".checkbox-column").change(function () {
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        console.log(data.length);
        $("#tombol-set-na").attr('disabled', true);
        if (data.length > 0) {
            $("#tombol-set-na").attr('disabled', false);
        }
    });
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items' => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                    'accesskey' => 't'
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items' => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
