<?php
/* @var $this ReturpenjualanController */
/* @var $model ReturPenjualan */

$this->breadcrumbs = array(
    'Retur Penjualan' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Retur Penjualan: {$model->nomor}";
?>

<div class="row">
    <div class="large-7 columns header">
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span><br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-retur-penjualan"><?php echo $model->total; ?></span>
    </div>
    <div class="large-5 columns">
        <?php
        echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Retur Jual', $this->createUrl('simpan', array(
                    'id' => $model->id
                )), array(
            'data' => "simpan=true",
            'type' => 'POST',
            'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }'
                ), array(
            'class' => 'tiny bigfont button right',
            'accesskey' => 's'
                )
        );
        ?>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_input_detail', array(
        'model' => $model,
    ));
    ?>
</div>
<div class="row" id="struk-list" style="display: none">
    <?php
    $this->renderPartial('_struk_list', array(
        'returPenjualan' => $model,
        'penjualanDetail' => $penjualanDetail,
    ));
    ?>
</div>
<div class="row" id="retur-penjualan-detail">
    <?php
    $this->renderPartial('_detail', array(
        'returPenjualan' => $model,
        'returPenjualanDetail' => $returPenjualanDetail
    ));
    ?>
</div>
<div class="row" id="barang-list" style="display:none">
    <?php
    $this->renderPartial('_barang_list', array(
        'barang' => $barang,
    ));
    ?>
</div>

<script>
    function updateTotal() {
        $("#total-retur-penjualan").load("<?php echo $this->createUrl('total', array('id' => $model->id)) ?>");
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
            array('label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'accesskey' => 'h',
                    'submit' => array('hapus', 'id' => $model->id),
                    'confirm' => 'Anda yakin?'
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
            array('label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'submit' => array('hapus', 'id' => $model->id),
                    'confirm' => 'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
