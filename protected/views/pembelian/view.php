<?php
/* @var $this PembelianController */
/* @var $model Pembelian */

$this->breadcrumbs = [
    'Pembelian' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Pembelian: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
        <br />
        <span class="secondary label label-total">Total</span><span class="alert label label-total"><?php echo $model->total; ?></span>

        <ul class="button-group right">
            <li>
                <a href="<?= $this->createUrl('retur', ['id' => $model->id]) ?>" class="tiny bigfont alert button" accesskey="r">
                    <i class="fa fa-reply fa-fw"></i>
                    <span class="ak">R</span>etur</a>
            </li>
            <li>
                <a href="#" accesskey="c" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown">
                    <i class="fa fa-print fa-fw"></i>
                    <span class="ak">C</span>etak</a>
                <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printerPembelian as $printer) {
                        if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                            /* Jika printer pdf, tambahkan pilihan ukuran kertas */
                    ?>
                            <span class="sub-dropdown">
                                <?php echo $printer['nama']; ?>
                                <small>
                                    <?php echo $printer['keterangan']; ?>
                                </small>
                            </span>
                            <ul>
                                <?php
                                foreach ($kertasUntukPdf as $key => $value) :
                                ?>
                                    <li>
                                        <a href="<?php echo $this->createUrl('printpembelian', ['id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key]) ?>">
                                            <?php echo $value; ?>
                                        </a>
                                    </li>
                                <?php
                                endforeach;
                                ?>
                            </ul>
                        <?php
                        } else {
                        ?>
                            <li>
                                <a href="<?php echo $this->createUrl('printpembelian', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                    <?php echo $printer['nama']; ?>
                                    <small>
                                        <?php echo $printer['keterangan']; ?>
                                    </small>
                                </a>
                            </li>
                    <?php
                        }
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
        $kolom = [
            [
                'class'     => 'BDataColumn',
                'name'      => 'barcode',
                'header'    => '<span class="ak">B</span>arcode',
                'accesskey' => 'b',
                'value'     => '$data->barang->barcode',
            ],
            [
                'class'     => 'BDataColumn',
                'name'      => 'namaBarang',
                'value'     => '$data->barang->nama',
                'header'    => '<span class="ak">N</span>ama Barang',
                'accesskey' => 'n',
            ],
            [
                'name'              => 'qty',
                'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false,
            ],
            [
                'name'              => 'harga_beli',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_beli, 0, ",", ".")',
                'filter'            => false,
            ],
            [
                'name'              => 'harga_jual',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_jual, 0, ",", ".")',
                'filter'            => false,
            ],
            [
                'name'              => 'subTotal',
                'header'            => 'Total',
                'value'             => '$data->total',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false,
            ],
        ];
        if ($showCurrentStock) {
            $kolom[] = [
                'name'              => 'Stok',
                'value'             => '$data->stok',
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                'filter'            => false,
            ];
        }
        $this->widget('BGridView', [
            'id'           => 'pembelian-detail-grid',
            'dataProvider' => $pembelianDetail->search('t.id'),
            'filter'       => $pembelianDetail,
            'summaryText'  => '{start}-{end} dari {count}, Total: <span class="label-total">' . $model->total.'</span>',
            'columns'      => $kolom,
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
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
        'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => false,
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