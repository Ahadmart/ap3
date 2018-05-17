<?php
/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = array(
    'Barang' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Barang: ' . $model->nama;
?>
<div class="row">
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_view_barang', array(
                'model' => $model
            ))
            ?>
        </div>
        <div class="panel">
            <?php
            $this->renderPartial('_view_harga_jual_multi', array(
                'hjMultiList' => $hjMultiList
            ))
            ?>
        </div>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_view_harga_jual', array(
                'hargaJual' => $hargaJual
            ))
            ?>
        </div>
        <div class="panel">
            <?php
//            $this->renderPartial('_view_harga_jual_rekomendasi', array(
//                'rrp' => $rrp
//            ))
            $this->renderPartial('_view_tag', ['curTags'=>$curTags]);
            ?>
        </div>
    </div>
    <div class="medium-12 large-5 columns end">
        <div class="panel">
            <?php
            $this->renderPartial('_view_inventory_balance', array(
                'inventoryBalance' => $inventoryBalance
            ))
            ?>
        </div>
        <div class="panel">
            <?php
            $this->renderPartial('_view_supplier_barang', array(
                'supplierBarang' => $supplierBarang
            ))
            ?>
        </div>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 'u'
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
            array('label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
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
