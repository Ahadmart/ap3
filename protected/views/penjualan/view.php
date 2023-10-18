<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = [
    'Penjualan' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan: ' . $model->nomor;

// Agar total terformat
$model->scenario = 'tampil';
?>
<div class="row">
    <div class="small-12 columns">
        <ul class="button-group">
            <li><a href="<?php echo $this->createUrl('exportcsv', ['id' => $model->id]); ?>" class="tiny bigfont success button">Export <span class="ak">C</span>SV</a></li>
            <li>
                <button href="#" accesskey="p" data-dropdown="printinvoice" aria-controls="printinvoice" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text fa-fw"></i> <span class="ak">P</span>rint Invoice (rrp)</button><br>
                <ul id="printinvoice" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerInvoiceRrp as $printer) {
                    ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printinvoice', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <button href="#" accesskey="t" data-dropdown="printnota" aria-controls="printnota" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text-o fa-fw"></i> Print No<span class="ak">t</span>a</button><br>
                <ul id="printnota" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerNota as $printer) {
                    ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printnota', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
            <li>
                <button href="#" accesskey="k" data-dropdown="printstruk" aria-controls="printstruk" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-file-text-o fa-fw"></i> Print Stru<span class="ak">k</span></button><br>
                <ul id="printstruk" data-dropdown-content class="f-dropdown" aria-hidden="true">
                    <?php
                    foreach ($printerStruk as $printer) {
                    ?>
                        <li>
                            <a href="<?php echo $this->createUrl('printstruk', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="small-12 columns header">
        <?php
        if ($model->transfer_mode) {
        ?>
            <span class="warning label">Transfer Barang</span>
        <?php
        }
        ?>
        <span class="secondary label">Customer</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span><br />
        <span class="secondary label label-total">Total</span><span class="alert label label-total"><?php echo $model->total; ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12  columns">
        <?php
        $this->widget('BGridView', [
            'id'           => 'penjualan-detail-grid',
            'dataProvider' => $penjualanDetail->search(),
            'filter'       => $penjualanDetail,
            'columns'      => [
                [
                    'name'  => 'barcode',
                    'value' => '$data->barang->barcode',
                ],
                [
                    'name'  => 'namaBarang',
                    'value' => '$data->barang->nama',
                ],
                [
                    //'name' => 'harga_jual',
                    'header'            => 'HPP',
                    'type'              => 'raw',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                    'value'             => [$this, 'tampilkanHargaBeli'],
                ],
                [
                    'name'              => 'qty',
                    'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                ],
                /*
        array(
        'name' => 'harga_beli',
        'htmlOptions' => array('class' => 'rata-kanan'),
        'value' => function($data) {
        return number_format($data->harga_beli, 0, ',', '.');
        }
        ),
         */
                [
                    'name'              => 'harga_jual',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                    'value'             => [$this, 'formatHargaJual'],
                ],
                [
                    'name'              => 'harga_jual_rekomendasi',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                    'value'             => [$this, 'formatHargaJualRekomendasi'],
                ],
                [
                    'name'              => 'subTotal',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false,
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'       => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-download"></i> I<span class="ak">m</span>port', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class'     => 'warning button',
                'accesskey' => 'm',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
    [
        'itemOptions'       => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'          => [
            ['label' => '<i class="fa fa-download"></i>', 'url' => $this->createUrl('import'), 'linkOptions' => [
                'class' => 'warning button',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
?>