<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = [
    'Retur Pembelian' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Retur Pembelian: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Supplier</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Reff</span><span class="label"><?php echo empty($model->referensi) ? '-' : $model->referensi; ?></span><span class="success label"><?php echo empty($model->tanggal_referensi) ? '-' : $model->tanggal_referensi; ?></span>
        <span class="secondary label">Status</span><span class="warning label"><?php
                                                                                echo $model->getNamaStatus();
                                                                                if ($model->status == ReturPembelian::STATUS_BATAL) {
                                                                                    $updatedAt = date_format(date_create_from_format(
                                                                                        'Y-m-d H:i:s',
                                                                                        $model->updated_at
                                                                                    ), 'd-m-Y H:i:s');

                                                                                    echo " ({$updatedAt})";
                                                                                }
                                                                                ?></span><br />
        <span class="secondary label label-total">Total</span><span class="alert label label-total"><?php echo $model->total; ?></span>

        <ul class="button-group right">
            <?php
            if ($model->status == ReturPembelian::STATUS_POSTED) :
            ?>
                <li><a href="<?= $this->createUrl('piutang', ['id' => $model->id]) ?>" class="tiny bigfont button" accesskey="t"><i class="fa fa-credit-card-alt fa-fw"></i> Terbitkan piu<span class="ak">t</span>ang</a></li>
            <?php
            endif;
            ?>
            <?php
            if ($model->status == ReturPembelian::STATUS_PIUTANG || $model->status == ReturPembelian::STATUS_LUNAS) {
            ?>
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
                                    foreach ($kertasUntukPdf as $key => $value) :
                                    ?>
                                        <li><a href="<?php echo $this->createUrl('printreturpembelian', ['id' => $model->id, 'printId' => $printer['id'], 'kertas' => $key]) ?>"><?php echo $value; ?></a></li>
                                    <?php
                                    endforeach;
                                    ?>
                                </ul>
                            <?php
                            } else {
                            ?>
                                <li>
                                    <a href="<?php echo $this->createUrl('printreturpembelian', ['id' => $model->id, 'printId' => $printer['id']]) ?>">
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
            <?php
            }
            ?>

            <?php
            if ($model->status == ReturPembelian::STATUS_POSTED) :
            ?>
                <li>
                    <?php
                    echo CHtml::ajaxLink(
                        '<i class="fa fa-times-rectangle fa-fw"></i> Bata<span class="ak">l</span>',
                        $this->createUrl('batal', [
                            'id' => $model->id,
                        ]),
                        [
                            'data'    => "batal=true",
                            'type'    => 'POST',
                            'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }',
                        ],
                        [
                            'class'     => 'tiny bigfont alert button',
                            'accesskey' => 's',
                        ]
                    );
                    ?>
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
        $this->widget('BGridView', [
            'id'            => 'retur-pembelian-detail-grid',
            'dataProvider'  => $returPembelianDetail->search(),
            // 'filter' => $returPembelianDetail,
            'enableSorting' => false,
            'columns'       => [
                [
                    'name'  => 'barcode',
                    'value' => '$data->inventoryBalance->barang->barcode',
                ],
                [
                    'name'  => 'namaBarang',
                    'value' => '$data->inventoryBalance->barang->nama',
                ],
                [
                    'name'  => 'pembelian',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->nomor',
                ],
                [
                    'name'  => 'tglPembelian',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal',
                ],
                [
                    'name'  => 'faktur',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->referensi',
                ],
                [
                    'name'  => 'tglFaktur',
                    'value' => '$data->inventoryBalance->pembelianDetail == null ? "":$data->inventoryBalance->pembelianDetail->pembelian->tanggal_referensi',
                ],
                [
                    'name'              => 'hargaBeli',
                    'value'             => 'number_format($data->inventoryBalance->harga_beli,0,",",".")',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'name'              => 'qty',
                    /*
            'value' => function($data) {
            return '<a href="#" class="editable-qty" data-type="text" data-pk="'.$data->id.'" data-url="'.Yii::app()->controller->createUrl('updateqty').'">'.
            $data->qty.'</a>';
            },
             */
                    'type'              => 'raw',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'header'            => 'Sub Total',
                    'value'             => '$data->subTotal',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
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
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'          => [
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
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions' => ['class' => 'button-group'],
    ],
];
