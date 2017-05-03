<?php
/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = array(
    'Barang' => array('index'),
    $model->id => array('view', 'id' => $model->id),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Barang: {$model->nama}";
?>
<div class="row">
    <div class="large-4 columns">
        <div class="panel">
            <h4><small>Ubah</small> Barang</h4>
            <hr />
            <?php $this->renderPartial('_form', array('model' => $model)); ?>
        </div>
    </div>

    <div class="large-8 columns">
        <div class="panel">
            <?php
            /*
             * Informasi & Form Supplier
             */
            $this->renderPartial('_supplier', array(
                'model' => $model,
                'supplierBarang' => $supplierBarang,
                'listBukanSupplier' => $listBukanSupplier,
            ));
            ?>
        </div>
    </div>
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            /*
             * Informasi dan form harga jual
             */
            $this->renderPartial('_harga_jual', array(
                'barang' => $model,
                'hargaJual' => $hargaJual
            ));
            ?>
        </div>
    </div>
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_tag', array(
                'barang' => $model,
                'curTags' => $curTags
            ));
            ?>
        </div>
    </div>
    <?php
    /* Disable RRP
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_harga_jual_rrp', array(
                'barang' => $model,
                'rrp' => $rrp
            ));
            ?>
        </div>
    </div>
     *
     */
    ?>
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
