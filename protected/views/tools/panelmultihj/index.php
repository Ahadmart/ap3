<?php
    /* @var $this PanelmultihjController */
    /* @var $model Barang */

    $this->breadcrumbs = [
        'Panel Multi Harga Jual' => ['index'],
        'Index',
    ];

    $this->boxHeader['small']  = 'Panel Multi HJ';
    $this->boxHeader['normal'] = 'Panel Multi (satuan) Harga Jual';

    // Agar focus tetap di input cari barcode setelah pencarian
    Yii::app()->clientScript->registerScript('barcodeFocus', ''
        . '$(document).ajaxComplete(function() {'
        . '$("input[name=\'Barang[barcode]\'").select();'
        . '});');
?>

<div id="update-multihj-m" class="small reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>

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
                        'class'     => 'BDataColumn',
                        'name'      => 'barcode',
                        'header'    => '<span class="ak">B</span>arcode',
                        'accesskey' => 'b',
                        'autoFocus' => true,
                        'value'     => [$this, 'renderGridCell'],
                    ],
                    [
                        'class'     => 'BDataColumn',
                        'name'      => 'namaBarang',
                        'header'    => '<span class="ak">N</span>ama',
                        'accesskey' => 'n',
                        'type'      => 'raw',
                        'value'     => [$this, 'renderGridCell'],
                    ],
                    [
                        'name'      => 'namaSatuan',
                        'value'     => [$this, 'renderGridCell'],
                        'filter'    => Barang::model()->filterSatuan(),
                    ],
                    [
                        'name'              => 'hargaJual',
                        'header'            => 'Harga Jual',
                        'value'             => [$this, 'renderGridCell'],
                        'htmlOptions'       => ['class' => 'rata-kanan'],
                        'headerHtmlOptions' => ['class' => 'rata-kanan'],
                        'filter'            => false,
                    ],
                    [
                        'name'              => 'satuan_id',
                        'header'            => 'Satuan (multi)',
                        'value'             => '$data->satuan->nama',
                        'htmlOptions'       => ['class' => 'rata-kanan'],
                        'filter'            => Barang::model()->filterSatuan(),
                    ],
                    [
                        'name'              => 'qty',
                        'htmlOptions'       => ['class' => 'rata-kanan'],
                        'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    ],
                    [
                        'name'              => 'harga',
                        'htmlOptions'       => ['class' => 'rata-kanan'],
                        'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    ],
                ],
            ]);
        ?>
    </div>
</div>
<script>    
    $("body").on("click", "a.namabarang-link", function () {
        $('#update-multihj-m').foundation('reveal', 'open', {
                url: '<?=$this->createUrl('formupdatemultihj', ['id' => '']);?>'+$(this).attr('href'),
                success: function (data) {
                    // Tampilkan Form
                },
                error: function () {
                    alert('Gagal mengambil data barang!');
                }
            });
        return false;
    
    });
</script>