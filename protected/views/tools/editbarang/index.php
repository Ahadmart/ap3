<?php
/* @var $this EditbarangController */
/* @var $model Barang */

$this->breadcrumbs = [
    'Barang' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Edit Barang';
$this->boxHeader['normal'] = 'Edit Barang';

// Agar focus tetap di input cari barcode setelah pencarian
Yii::app()->clientScript->registerScript('barcodeFocus', ''
    . '$(document).ajaxComplete(function() {'
    . '$("input[name=\'Barang[barcode]\'").select();'
    . '});');
?>

<div id="ganti-rak-m" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div id="ganti-kat-m" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div id="edit-sup-m" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div id="ganti-struktur-m" class="medium reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div id="ganti-minrestock-m" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div class="row">
    <div class="small-12 columns">
        <?= CHtml::link('Set Non Aktif', '#', ['class' => 'button tb-edit-barang', 'id' => 'tombol-set-na', 'disabled' => true]); ?>
        <?= CHtml::link('Set Aktif', '#', ['class' => 'button tb-edit-barang', 'id' => 'tombol-set-a', 'disabled' => true]); ?>
        <?=
        CHtml::link('Ganti Rak..', '#', [
            'class'          => 'button tb-edit-barang',
            'data-reveal-id' => 'ganti-rak-m',
            'id'             => 'tombol-ganti-rak',
            'disabled'       => true
        ])
        ?>
        <?=
        CHtml::link('Ganti Kategori..', '#', [
            'class'          => 'button tb-edit-barang',
            'data-reveal-id' => 'ganti-kat-m',
            'id'             => 'tombol-ganti-kat',
            'disabled'       => true
        ])
        ?>
        <?=
        CHtml::link('Edit Supplier..', '#', [
            'class'          => 'button tb-edit-barang',
            'data-reveal-id' => 'edit-sup-m',
            'id'             => 'tombol-edit-sup',
            'disabled'       => true
        ])
        ?>
        <?=
        CHtml::link('Ganti Struktur..', '#', [
            'class'    => 'button tb-edit-barang',
            //'data-reveal-id' => 'ganti-struktur-m',
            'id'       => 'tombol-ganti-struktur',
            'disabled' => true
        ])
        ?>
        <?=
        CHtml::link('Set Minimum Restock..', '#', [
            'class'    => 'button tb-edit-barang',
            //'data-reveal-id' => 'ganti-struktur-m',
            'id'       => 'tombol-ganti-minrestock',
            'disabled' => true
        ])
        ?>
    </div>
</div>
<div id="ganti-struktur" style="display:none">
    <div class="panel">
        <?php
        $this->renderPartial('_form_ganti_struktur', [
            'lv1'           => $lv1,
            'strukturDummy' => $strukturDummy
        ]);
        ?>
    </div>
</div>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'barang-grid',
            'dataProvider'  => $model->search(50),
            'filter'        => $model,
            'itemsCssClass' => 'tabel-index',
            'columns'       => [
                [
                    'id'             => 'kolomcek',
                    'class'          => 'CCheckBoxColumn',
                    'selectableRows' => 2,
                    'value'          => '$data->id',
                ],
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
                ],
                [
                    'class' => 'BDataColumn',
                    'name'  => 'daftarSupplier',
                    'type'  => 'raw',
                    'value' => [$this, 'renderSuppliers'],
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
                    'name'   => 'restock_min',
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
                    'name'   => 'Pembelian Terakhir',
                    'value'  => '$data->tanggalBeliTerakhir == null ? "NULL" : $data->tanggalBeliTerakhir',
                    'filter' => false,
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
    $(document).ajaxComplete(function() {
        cekboxchange();
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
        $(".tb-edit-barang").attr("disabled", false);
    }

    function DisableTombol() {
        $(".tb-edit-barang").attr("disabled", true);
    }

    $("#tombol-set-na").click(function() {
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
                success: function(data) {
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

    $("#tombol-set-a").click(function() {
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
                success: function(data) {
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

    $("#tombol-ganti-rak").click(function() {
        if ($(this).is("[disabled]")) {
            console.log('set rak disabled clicked');
        } else {
            $('#ganti-rak-m').foundation('reveal', 'open', {
                url: '<?= $this->createUrl('formgantirak'); ?>',
                success: function(data) {
                    // Tampilkan Dropdown pilihan rak
                },
                error: function() {
                    alert('Gagal mengambil data rak!');
                }
            });
        }
    });

    $("#tombol-ganti-kat").click(function() {
        if ($(this).is("[disabled]")) {
            console.log('ganti kat disabled clicked');
        } else {
            $('#ganti-kat-m').foundation('reveal', 'open', {
                url: '<?= $this->createUrl('formgantikat'); ?>',
                success: function(data) {
                    // Tampilkan Dropdown pilihan kategori
                },
                error: function() {
                    alert('Gagal mengambil data kategori!');
                }
            });
        }
    });

    $("#tombol-edit-sup").click(function() {
        if ($(this).is("[disabled]")) {
            console.log('edit sup disabled clicked');
        } else {
            $('#edit-sup-m').foundation('reveal', 'open', {
                url: '<?= $this->createUrl('formeditsup'); ?>',
                success: function(data) {
                    // Tampilkan Dropdown pilihan rak
                },
                error: function() {
                    alert('Gagal mengambil data supplier!');
                }
            });
        }
    });

    $("#tombol-ganti-struktur").click(function() {
        if ($(this).is("[disabled]")) {
            console.log('set na disabled clicked');
        } else {
            $("#ganti-struktur").toggle(500);
        }
    })

    $("#tombol-ganti-minrestock").click(function() {
        if ($(this).is("[disabled]")) {
            console.log('ganti restock minimum disabled clicked');
        } else {

            $('#ganti-minrestock-m').foundation('reveal', 'open', {
                url: '<?= $this->createUrl('formminimumrestock'); ?>',
                success: function(data) {
                    // Tampilkan Input Minimum Restock
                },
                error: function() {
                    alert('Gagal mengambil data form ganti restock!');
                }
            });
        }
    });
</script>