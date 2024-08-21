<?php
/* @var $this SkuController */
/* @var $model Sku */

$this->breadcrumbs = [
    'SKU'      => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "SKU: ({$model->nomor}) {$model->nama}";
?>

<div id="tambahbarang-list" class="medium reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>

<div class="row">
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php $this->renderPartial('_form', ['model' => $model]); ?>
        </div>
    </div>
    <div class="medium-6 large-8 columns">
        <div class="panel">
            <!-- <a class="right tiny bigfont button">Tambah barang</a> -->
            <?=
            CHtml::link('Tambah Barang', '#', [
                'class'          => 'right tiny bigfont button',
                'data-reveal-id' => 'tambahbarang-list',
                'id'             => 'tombol-tambahbarang',
            ])
            ?>
            <?php $this->renderPartial('_ubah_detail', ['modelDetail' => $modelDetail]); ?>
        </div>
    </div>
</div>
<script>
    $("#tombol-tambahbarang").click(function() {
        $('#tambahbarang-list').foundation('reveal', 'open', {
            url: '<?= $this->createUrl('tambahbaranglist'); ?>',
            success: function(data) {
                // Ensure any necessary scripts are processed
                // if (typeof $.fn.yiiGridView !== 'undefined') {
                //     $.each($.fn.yiiGridView.settings, function(id, settings) {
                //         $('#' + id).yiiGridView(settings);
                //     });
                // }
                inputBarang = $("input[name='Barang[barcode]']")
                setTimeout(function() {
                    inputBarang.focus();
                }, 500);

                // inputBarang.focus()
                // inputBarang.focus()
            },
            error: function() {
                alert('Gagal mengambil data barang!');
            }
        });
    });

    $("body").on("click", "a.pilih.barang", function() {
        var barangId = $(this).data('id');
        var dataUrl = "<?= $this->createUrl('tambahbarang') ?>";
        var dataKirim = {
            id: <?= $model->id ?>,
            barangId: barangId
        }
        $.ajax({
            type: "POST",
            url: dataUrl,
            data: dataKirim,
            dataType: "json",
            success: function(data) {

            }
        });
        return false;
    });
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't'
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
?>