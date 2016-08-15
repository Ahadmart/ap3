<?php
/* @var $this PembelianController */
/* @var $model Pembelian */

$this->breadcrumbs = array(
    'Pembelian' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Pembelian: {$model->nomor}";
?>
<div class="row">
    <div class="medium-5 large-5 columns">
        <?php
        echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Pembelian', $this->createUrl('simpanpembelian', array('id' => $model->id)), array(
            'data' => "simpan=true",
            'type' => 'POST',
            'beforeSend' => 'function() {
                                $("#tombol-simpan").addClass("warning");
                                $("#tombol-simpan").html("<i class=\"fa fa-floppy-o fa-spin\"></i> <span class=\"ak\">S</span>impan Pembelian");
                            }',
            'complete' => 'function() {
                                $("#tombol-simpan").removeClass("warning");
                            }',
            'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }'
                ), array(
            'class' => 'tiny bigfont button',
            'accesskey' => 's',
            'id' => 'tombol-simpan'
                )
        );
        ?>
        <?php
        echo CHtml::link('<i class="fa fa-times"></i> Bata<span class="ak">l</a>', $this->createUrl('hapus', array('id' => $model->id)), array(
            'class' => 'alert tiny bigfont button',
            'accesskey' => 'l'
        ));
        ?>
    </div>
    <div class="medium-7 large-7 columns header" style="text-align: right">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <span class="secondary label">Total</span><span class="label" id="total-pembelian"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <?php
    if ($pilihBarang) {
        $this->renderPartial('_pilih_barang', array(
            'pembelianModel' => $model,
            'barangBarcode' => $barangBarcode,
            'barangNama' => $barangNama,
            'barang' => $barang,
            'pembulatan' => $pembulatan,
            'barangList' => $barangList
        ));
    }
    ?>
    <div class="small-12 columns">
        <span class="warning label" style="font-weight: bold">BARANG BARU</span>
        <span class="success label" style="font-weight: bold">HARGA JUAL BERUBAH</span>
    </div>
</div>

<div class="row">
    <?php
    $this->renderPartial('_detail', array(
        'pembelian' => $model,
        'pembelianDetail' => $pembelianDetail,
    ));
    ?>
</div>
<script>

    function updateTotal() {
        var dataurl = "<?php echo $this->createUrl('total', array('id' => $model->id)); ?>";
        $.ajax({
            url: dataurl,
            type: "GET",
            success: function (data) {
                if (data.sukses) {
                    $("#total-pembelian").text(data.totalF);
                    console.log(data.totalF);
                }
            }
        });
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
