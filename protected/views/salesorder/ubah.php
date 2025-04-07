<?php
/* @var $this SalesorderController */
/* @var $model So */

$this->breadcrumbs = [
    'Pesanan Penjualan' => ['index'],
    $model->id          => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Sales Order: {$model->nomor}";
?>
<div class="row">
    <div class="large-7 columns header">
        <span class="secondary label">Customer</span><span class="label"><?= $model->profil->nama; ?></span><br />
        <span class="secondary label label-total">Total</span><span class="label label-total" id="total-pesanan"><?= $model->total; ?></span>
    </div>
    <div class="large-5 columns">
        <ul class="button-group right">
            <?php
            if ($model->status == So::STATUS_DRAFT) {
                ?>
                <li>
                    <?php
                    echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> Pe<span class="ak">s</span>an',
                            $this->createUrl('pesan', ['id' => $model->id]),
                            [
                        'data'    => "order=true",
                        'type'    => 'POST',
                        'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();;
                            }
                        }'
                            ],
                            [
                        'class'     => 'tiny bigfont button',
                        'accesskey' => 's'
                            ]
                    );
                    ?>
                </li>
                <?php
            }
            ?>
            <?php
            if ($model->status == So::STATUS_PESAN) {
                ?>
                <li>
                    <button href="#" accesskey="k" data-dropdown="printstruk" aria-controls="printstruk" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-print fa-fw"></i> Print Stru<span class="ak">k</span></button><br>
                    <ul id="printstruk" data-dropdown-content class="f-dropdown" aria-hidden="true">
                        <?php
                        foreach ($printerStruk as $printer) {
                            ?>
                            <li>
                                <a href="<?php
                                echo $this->createUrl('printstruk', ['id' => $model->id, 'printId' => $printer['id']])
                                ?>">
                                    <?php echo $printer['nama']; ?> <small><?php echo $printer['keterangan']; ?></small></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </li>
                <?php
            }
            ?>
            <li>
                <?php
                echo CHtml::link('<i class="fa fa-times"></i> Bata<span class="ak">l</a>',
                        $this->createUrl('batal', ['id' => $model->id]),
                        [
                    'class'     => 'alert tiny bigfont button',
                    'accesskey' => 'l'
                ]);
                ?>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <?php
    $this->renderPartial('_input_detail', [
        'model' => $model,
    ]);
    ?>
</div>
<div class="row" id="sales-order-detail">
    <?php
    $this->renderPartial('_detail', [
        'model'       => $model,
        'modelDetail' => $modelDetail
    ]);
    ?>
</div>
<div class="row" id="barang-list" style="display:none">
    <?php
    $this->renderPartial('_barang_list', [
        'barang' => $barang,
    ]);
    ?>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'],
        'label'          => false,
        'items'          => [
            [
                'label'       => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah',
                'url'         => $this->createUrl('tambah'),
                'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 't'
                ]],
            [
                'label'       => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class'     => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'],
        'label'          => false,
        'items'          => [
            [
                'label'       => '<i class="fa fa-plus"></i>',
                'url'         => $this->createUrl('tambah'),
                'linkOptions' => [
                    'class' => 'button',
                ]],
            [
                'label'       => '<i class="fa fa-asterisk"></i>',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class' => 'success button',
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
