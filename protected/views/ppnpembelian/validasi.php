<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs = [
    'Pembelian Ppn' => ['index'],
    $model->id      => ['view', 'id' => $model->id],
    'Validasi',
];

$this->boxHeader['small']  = 'Validasi';
$this->boxHeader['normal'] = "Validasi Ppn Pembelian: {$model->pembelian->nomor}";
?>
<div class="row">
    <div class="large-6 columns">
        <?php $this->renderPartial('_form_validasi', ['model' => $model]); ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
    [
        'itemOptions'          => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
];
