<?php
/* @var $this PesananpenjualanController */
/* @var $model PesananPenjualan */

$this->breadcrumbs = [
    'Pesanan Penjualan' => ['index'],
    $model->id          => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Pesanan Penjualan: {$model->nomor}";
?>
<div class="row">
    <div class="large-7 columns header">
        <span class="secondary label">Customer</span><span class="label"><?= $model->profil->nama; ?></span>
        <span class="secondary label">Total</span><span class="label" id="total-pesanan"><?= $model->total;     ?></span>
    </div>
    <div class="large-5 columns">
        <ul class="button-group right">
            <li>
                <?php
                echo CHtml::ajaxLink('<i class="fa fa-floppy-o"></i> <span class="ak">S</span>impan Pesanan',
                        $this->createUrl('simpan', ['id' => $model->id]),
                        [
                    'data'    => "simpan=true",
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
<div class="row" id="pesanan-penjualan-detail">
    <?php
    $this->renderPartial('_detail', [
        'model'       => $model,
        'modelDetail' => $modelDetail
    ]);
    ?>
</div>
<?php
$this->menu                = [
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
