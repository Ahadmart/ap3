<?php
/* @var $this EditbarangController */
/* @var $model Barang */

$this->breadcrumbs = [
    'Barang' => ['index'],
    'Index',
];

$this->boxHeader['small'] = 'Edit Barang';
$this->boxHeader['normal'] = 'Edit Barang';

// Agar focus tetap di input cari barcode setelah pencarian
Yii::app()->clientScript->registerScript('barcodeFocus', ''
        . '$( document ).ajaxComplete(function() {'
        . '$("input[name=\'Barang[barcode]\'").select();'
        . '});');
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?= CHtml::link('Set Non Aktif', '#', ['class' => 'button', 'id' => 'tombol-set-na', 'disabled' => true]) ?>
        <?= CHtml::link('Set Aktif', '#', ['class' => 'button', 'id' => 'tombol-set-a', 'disabled' => true]) ?>
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
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $(document).ajaxComplete(function () {
        DisableTombol();
        $(".checkbox-column").change(cekboxchange);
    });

    $(".checkbox-column").change(cekboxchange);

    function cekboxchange() {
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        console.log(data.length);
        DisableTombol();
        if (data.length > 0) {
            enableTombol();
        }
    }

    function enableTombol() {
        $("#tombol-set-na").attr('disabled', false);
        $("#tombol-set-a").attr('disabled', false);
    }

    function DisableTombol() {
        $("#tombol-set-na").attr('disabled', true);
        $("#tombol-set-a").attr('disabled', true);

    }

    $("#tombol-set-na").click(function () {
        if ($(this).is("[disabled]")) {
            console.log('set na disabled clicked');
        } else {
            var dataUrl = '<?= $this->createUrl('setna'); ?>';
            var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
            var dataKirim = {
                'ajaxdata': true,
                'items': data
            };
            console.log(dataKirim);
            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.gritter.add({
                            title: 'Sukses',
                            text: data.rowAffected + ' item di NON Aktifkan',
                            time: 3000
                        });
                        $('#barang-grid').yiiGridView('update');
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 5000
                        });
                    }
                }
            });
        }
    });

    $("#tombol-set-a").click(function () {
        if ($(this).is("[disabled]")) {
            console.log('set a disabled clicked');
        } else {
            var dataUrl = '<?= $this->createUrl('seta'); ?>';
            var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
            var dataKirim = {
                'ajaxdata': true,
                'items': data
            };
            console.log(dataKirim);
            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.gritter.add({
                            title: 'Sukses',
                            text: data.rowAffected + ' item di Aktifkan',
                            time: 3000
                        });
                        $('#barang-grid').yiiGridView('update');
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 5000
                        });
                    }
                }
            });
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
