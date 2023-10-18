<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = [
    'Retur Pembelian' => ['index'],
    $model->id        => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Retur Pembelian: {$model->nomor}";

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="medium-6 large-5 columns">
        <?php
        echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Retur Pembelian', $this->createUrl('simpan', ['id' => $model->id]), [
            'data'    => 'simpan=true',
            'type'    => 'POST',
            'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload(true);
                            } else {
                                $.gritter.add({
                                    title: "Error " + data.error.code,
                                    text: data.error.msg,
                                    time: 3000,
                                });
                            }
                        }',
        ], [
            'class'     => 'tiny bigfont button',
            'accesskey' => 's',
        ]);
        ?>
    </div>
    <div class="medium-6 large-7 columns header" style="text-align: right">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span><br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-retur-pembelian"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_pilih_barang', [
        'pembelianModel'   => $model,
        //'barangBarcode' => $barangBarcode,
        //'barangNama' => $barangNama,
        'inventoryBalance' => $inventoryBalance,
        'model'            => $model,
        'scanBarcode'      => $scanBarcode,
    ]);
    ?>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->renderPartial('_detail', [
            'returPembelian'       => $model,
            'returPembelianDetail' => $returPembelianDetail,
        ]);
        ?>
    </div>
</div>
<script>
    function updateTotal() {
        $("#total-retur-pembelian").load(
            "<?php echo $this->createUrl('total', ['id' => $model->id]) ?>"
        );
    }
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't',
            ]],
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'alert button',
                'accesskey' => 'h',
                'submit'    => ['hapus', 'id' => $model->id],
                'confirm'   => 'Anda yakin?',
            ]],
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
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'   => 'alert button',
                'submit'  => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
