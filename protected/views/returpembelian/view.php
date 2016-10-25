<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = array(
    'Retur Pembelian' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Retur Pembelian: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <!--<span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>-->
        <span class="secondary label">Total</span><span class="alert label"><?php echo $model->total; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>

        <ul class="button-group right">
            <li>
                <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printerReturPembelian as $printer) {
                        ?>
                        <?php
                        if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                            /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                            ?>
                            <span class="sub-dropdown"><?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></span>
                            <ul>
                                <?php
                                foreach ($kertasUntukPdf as $key => $value):
                                    ?>
                                    <li><a href="<?php echo $this->createUrl('printreturpembelian', array('id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key)) ?>"><?php echo $value; ?></a></li>
                                    <?php
                                endforeach;
                                ?>
                            </ul>
                            <?php
                        } else {
                            ?>
                            <li>
                                <a href="<?php echo $this->createUrl('printreturpembelian', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
                                    <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                            </li>
                            <?php
                        }
                        ?>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'retur-pembelian-detail-grid',
            'dataProvider' => $returPembelianDetail->search(),
            // 'filter' => $returPembelianDetail,
            'enableSorting' => false,
            'columns' => array(
                array(
                    'name' => 'barcode',
                    'value' => '$data->inventoryBalance->barang->barcode'
                ),
                array(
                    'name' => 'namaBarang',
                    'value' => '$data->inventoryBalance->barang->nama'
                ),
                array(
                    'name' => 'pembelian',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->nomor'
                ),
                array(
                    'name' => 'tglPembelian',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal'
                ),
                array(
                    'name' => 'faktur',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->referensi'
                ),
                array(
                    'name' => 'tglFaktur',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal_referensi'
                ),
                array(
                    'name' => 'hargaBeli',
                    'value' => 'number_format($data->inventoryBalance->harga_beli,0,",",".")',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'qty',
                    /*
                      'value' => function($data) {
                      return '<a href="#" class="editable-qty" data-type="text" data-pk="'.$data->id.'" data-url="'.Yii::app()->controller->createUrl('updateqty').'">'.
                      $data->qty.'</a>';
                      },
                     */
                    'type' => 'raw',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                ),
                array(
                    'header' => 'Sub Total',
                    'value' => '$data->subTotal',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                ),
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
