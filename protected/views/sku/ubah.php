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
<div id="tambahlevel-form" class="medium reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>
<div id="tambahhj-form" class="medium reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>

<div class="row">
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php $this->renderPartial('_form', ['model' => $model]); ?>
        </div>
        <div class="panel">
            <div class="row">
                <?=
                CHtml::link('Tambah Level', '#', [
                    'class'          => 'right tiny bigfont button',
                    'data-reveal-id' => 'tambahlevel-form',
                    'id'             => 'tombol-tambahlevel',
                ])
                ?>
            </div>
            <div class="row">
                <?php $this->renderPartial('_ubah_level', [
                    'modelLevel' => $modelLevel,
                    'levelMax'   => $levelMax
                ]); ?>
            </div>
        </div>
    </div>
    <div class="medium-6 large-8 columns">
        <div class="panel">
            <!-- <a class="right tiny bigfont button">Tambah barang</a> -->
            <div class="row">
                <?=
                CHtml::link('Tambah Barang', '#', [
                    'class'          => 'right tiny bigfont button',
                    'data-reveal-id' => 'tambahbarang-list',
                    'id'             => 'tombol-tambahbarang',
                ])
                ?>
            </div>
            <div class="row">
                <?php $this->renderPartial('_ubah_detail', ['modelDetail' => $modelDetail]); ?>
            </div>
        </div>
        <div class="panel">
            <?php
            $this->renderPartial('_struktur', [
                'sku'           => $model,
                'lv1'           => $lv1,
                'strukturDummy' => $strukturDummy,
            ]);
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="medium-6 large-4 columns">
    </div>

    <div class="medium-6 large-8 columns">

</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
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
    $("#tombol-tambahlevel").click(function() {
        $('#tambahlevel-form').foundation('reveal', 'open', {
            url: '<?= $this->createUrl('tambahlevelform', ['id' => $model->id]); ?>',
            success: function(data) {
                // Ensure any necessary scripts are processed
                // if (typeof $.fn.yiiGridView !== 'undefined') {
                //     $.each($.fn.yiiGridView.settings, function(id, settings) {
                //         $('#' + id).yiiGridView(settings);
                //     });
                // }
                // inputBarang = $("input[name='Barang[barcode]']")
                // setTimeout(function() {
                //     inputBarang.focus();
                // }, 500);

                // inputBarang.focus()
                // inputBarang.focus()
            },
            error: function() {
                alert('Gagal membuka form tambah level!');
            }
        });
    });
    $(".tombol-tambahhj").click(function() {
        var url = $(this).attr('href')
        console.log("Ini Urlnya" +url);
        $('#tambahhj-form').foundation('reveal', 'open', {
            url: $(this).attr('href'),
            // success: function(data) {

            // },
            error: function() {
                alert('Gagal mengambil data barang!');
            }
        });
    });
    // $(window).on("load", function() {
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
                if (data.sukses) {
                    console.log("Tambah barang ke sku_detail sukses")
                    location.reload();
                } else {
                    console.log("Gagal tambah barang ke sku_detail")
                }
            }
        });
        return false;
    });
    // })

    // $(window).on("load", function() {
    //     $.fn.yiiGridView.update('sku-detail-grid');
    // });
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions' => ['class' => 'has-form hide-for-small-only'],
        'label' => false,
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
        'itemOptions' => ['class' => 'has-form show-for-small-only'],
        'label' => false,
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