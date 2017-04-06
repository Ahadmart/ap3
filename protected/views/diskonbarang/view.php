<?php
/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

$this->breadcrumbs = array(
    'Diskon Barang' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$headerBoxSmall = 'Diskon Barang: ';
if (!is_null($model->barang_id)) {
    $headerBoxSmall .= $model->barang->nama;
} else {
    $headerBoxSmall .= 'Semua Barang';
}
$this->boxHeader['normal'] = $headerBoxSmall;
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BDetailView', array(
            'data' => $model,
            'attributes' => array(
                array(
                    'name' => 'barang.nama',
                    'label' => 'Nama'
                ),
                array(
                    'name' => 'barang.barcode',
                    'label' => 'Barcode'
                ),
                array(
                    'label' => 'Tipe',
                    'value' => $model->getNamaTipe()
                ),
                array(
                    'name' => 'barang.hargaJual',
                    'label' => 'Harga Jual Asli'
                ),
                array(
                    'name' => 'nominal',
                    'value' => number_format($model->nominal, 0, ',', '.')
                ),
                array(
                    'name' => 'persen',
                    'value' => number_format($model->persen, 2, ',', '.')
                ),
                'dari',
                'sampai',
                'qty',
                'qty_min',
                'qty_max',
                [
                    'name' => 'barangBonus.nama',
                    'label' => 'Barang Bonus'
                ],
                [
                    'name' => 'barangBonus.barcode',
                    'label' => 'Barcode Barang Bonus'
                ],
                [
                    'name' => 'barang_bonus_diskon_nominal',
                    'value' => number_format($model->barang_bonus_diskon_nominal, 0, ',', '.')
                ],
                [
                    'name' => 'barang_bonus_diskon_persen',
                    'value' => number_format($model->barang_bonus_diskon_persen, 2, ',', '.')
                ],
                array(
                    'label' => 'Status',
                    'value' => $model->namaStatus
                )
            ),
        ));
        ?>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
