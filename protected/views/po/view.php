<?php
/* @var $this PoController */
/* @var $model Po */

$this->breadcrumbs = [
    'Po' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'PO: ' . $model->nomor;

$this->pageTitle = Yii::app()->name . ' - ' . $this->boxHeader['normal'];
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
            <li><a href="<?= $this->createUrl('beli', ['id' => $model->id]) ?>" class="tiny bigfont alert button" accesskey="l"><i class="fa fa-truck fa-fw"></i> Pembe<span class="ak">l</span>ian</a></li>
            <?php
            if (!empty($printerPo)) :
            ?>
                <li>
                    <a href="#" accesskey="c" data-dropdown="print" aria-controls="print" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> <span class="ak">C</span>etak</a>
                    <ul id="print" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                        <?php
                        foreach ($printerPo as $printer) {
                        ?>
                            <?php
                            if ($printer['tipe_id'] == Device::TIPE_PDF_PRINTER) {
                                /* Jika printer pdf, tambahkan pilihan ukuran kertas */ ?>
                                <span class="sub-dropdown"><?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></span>
                                <ul>
                                    <?php
                                    foreach ($kertasUntukPdf as $key => $value) :
                                    ?>
                                        <li><a target="_blank" href="<?php echo $this->createUrl('print', ['id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key, 'harga_beli' => 1]) ?>"><?php echo $value; ?></a></li>
                                        <li><a target="_blank" href="<?php echo $this->createUrl('print', ['id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key, 'harga_beli' => 0]) ?>"><?php echo $value; ?> (tanpa harga beli)</a></li>
                                    <?php
                                    endforeach; ?>
                                </ul>
                            <?php
                            } else {
                            ?>
                                <li>
                                    <a href="<?php echo $this->createUrl('print', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
                                        <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                                </li>
                            <?php
                            } ?>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
            <?php
            endif;
            ?>
        </ul>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        if (isset($aPlsParam) && !is_null($aPlsParam)) {
        ?>
            <a href='#' data-reveal-id="aPlsParam-view" class="tiny button">Analisa PLS Parameter >></a>
            <div id='aPlsParam-view' class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
                <?php
                $this->widget('BDetailView', [
                    'data'       => $aPlsParam,
                    'attributes' => [
                        'range',
                        'order_period',
                        'lead_time',
                        'ssd',
                        [
                            'name'  => 'rak.nama',
                            'label' => 'Rak',
                        ],
                        [
                            'name'  => 'strukturLv1.nama',
                            'label' => 'Struktur Lv1',
                        ],
                        [
                            'name'  => 'strukturLv2.nama',
                            'label' => 'Struktur Lv2',
                        ],
                        [
                            'name'  => 'strukturLv3.nama',
                            'label' => 'Struktur Lv3',
                        ],
                    ]
                ]);
                ?>
            </div>
        <?php
        }
        ?>
        <?php
        $this->widget('BGridView', [
            'id'                    => 'po-detail-grid',
            'dataProvider'          => $poDetail->search('t.id'),
            'filter'                => $poDetail,
            'summaryText'           => '{start}-{end} dari {count}, Total: <span class="label-total">' . $model->total . '</span>',
            'rowCssClassExpression' => function ($row, $data) {
                if ($data->ads == 0) {
                    return 'manual';
                }
            },
            'columns'      => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'barcode',
                    'header'    => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nama',
                    'header'    => '<span class="ak">N</span>ama Barang',
                    'accesskey' => 'n',
                ],
                [
                    'name'              => 'ads',
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false
                ],
                [
                    'name'              => 'saran_order',
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false
                ],
                [
                    'name'              => 'qty_order',
                    'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false
                ],
                [
                    'name'              => 'harga_beli',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'value'             => 'number_format($data->harga_beli, 0, ",", ".")',
                    'filter'            => false
                ],
                [
                    'name'              => 'harga_jual',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'value'             => 'number_format($data->harga_jual, 0, ",", ".")',
                    'filter'            => false
                ],
                [
                    'name'              => 'subTotal',
                    'header'            => 'Total',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false
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
        'itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'u'
            ]],
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'alert button',
                'accesskey' => 'h',
                'submit'    => ['hapus', 'id' => $model->id],
                'confirm'   => 'Anda yakin?'
            ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'       => [
            ['label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', ['id' => $model->id]), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'   => 'alert button',
                'submit'  => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?'
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
?>