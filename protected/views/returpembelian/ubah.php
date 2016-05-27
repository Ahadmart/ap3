<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = array(
    'Retur Pembelian' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Retur Pembelian: {$model->nomor}";

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="medium-6 large-5 columns">
        <?php
        echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Retur Pembelian', $this->createUrl('simpan', array('id' => $model->id)), array(
            'data' => "simpan=true",
            'type' => 'POST',
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
                        }'
                ), array(
            'class' => 'tiny bigfont button',
            'accesskey' => 's'
                )
        );
        ?>
    </div>
    <div class="medium-6 large-7 columns header" style="text-align: right">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Total</span><span class="label" id="total-retur-pembelian"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_pilih_barang', array(
        'pembelianModel' => $model,
        //'barangBarcode' => $barangBarcode,
        //'barangNama' => $barangNama,
        'inventoryBalance' => $inventoryBalance,
        'model' => $model
    ));
    ?>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->renderPartial('_detail', array(
            'returPembelian' => $model,
            'returPembelianDetail' => $returPembelianDetail
        ));
        ?>
    </div>
</div>
<script>
    function updateTotal() {
        $("#total-retur-pembelian").load("<?php echo $this->createUrl('total', array('id' => $model->id)) ?>");
    }
</script>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
