<?php
/* @var $this ReturpenjualanController */
/* @var $model ReturPenjualan */

$this->breadcrumbs = array(
    'Retur Penjualan' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Retur Penjualan: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span><br />
        <span class="secondary label label-total">Total</span><span class="alert label label-total"><?php echo $model->total; ?></span>

        <ul class="button-group right">
            <li>
                <a href="#" accesskey="p" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printerReturPenjualan as $printer) {
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
                                    <li><a href="<?php echo $this->createUrl('printreturpenjualan', array('id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key)) ?>"><?php echo $value; ?></a></li>
                                    <?php
                                endforeach;
                                ?>
                            </ul>
                            <?php
                        } else {
                            ?>
                            <li>
                                <a href="<?php echo $this->createUrl('printreturpenjualan', array('id' => $model->id, 'printId' => $printer['id'])) ?>">
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
            'id' => 'retur-penjualan-detail-grid',
            'dataProvider' => $returPenjualanDetail->search(),
            //'filter' => $returPenjualanDetail,
            'enableSorting' => false,
            'columns' => array(
                array(
                    'name' => 'barcode',
                    'value' => '$data->penjualanDetail->barang->barcode',
                ),
                array(
                    'name' => 'namaBarang',
                    'value' => '$data->penjualanDetail->barang->nama',
                ),
                array(
                    'header' => 'Penjualan',
                    'value' => '$data->penjualanDetail->penjualan->nomor',
                ),
                array(
                    'header' => 'Tanggal Penjualan',
                    'value' => '$data->penjualanDetail->penjualan->tanggal',
                ),
                array(
                    'header' => 'Harga Jual',
                    'value' => 'number_format($data->penjualanDetail->harga_jual,0,",",".")',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan')
                ),
                array(
                    'name' => 'qty',
                    'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'subTotal',
                    'value' => 'number_format($data->total,0,",",".")',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false
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
