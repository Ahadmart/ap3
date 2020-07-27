<?php
/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = [
    'Barang'   => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Barang: {$model->nama}";
?>
<div class="row">
    <div class="large-4 columns">
        <div class="panel">
            <h4><small>Ubah</small> Barang</h4>
            <hr />
            <?php $this->renderPartial('_form', ['model' => $model]); ?>
        </div>
        <div class="panel">
            <?php 
            $this->renderPartial('_harga_jual_multi', [
                'barang'       => $model,
                'hjMultiModel' => $hjMultiModel,
                'hjMulti'      => $hjMulti,
                'hjMultiList'  => $hjMultiList,
                ]);
                ?>
        </div>
    </div>
    <div class="large-8 columns">
        <div class="panel">
            <?php
            /*
             * Informasi & Editable Struktur
             */
            $this->renderPartial('_struktur', [
                'barang'        => $model,
                'lv1'           => $lv1,
                'strukturDummy' => $strukturDummy
            ]);
            ?>
        </div>
    </div>
    <div class="large-8 columns">
        <div class="panel">
            <?php
            /*
             * Informasi & Form Supplier
             */
            $this->renderPartial('_supplier', [
                'model'             => $model,
                'supplierBarang'    => $supplierBarang,
                'listBukanSupplier' => $listBukanSupplier,
            ]);
            ?>
        </div>
    </div>
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            /*
             * Informasi dan form harga jual
             */
            $this->renderPartial('_harga_jual', [
                'barang'    => $model,
                'hargaJual' => $hargaJual
            ]);
            ?>
        </div>
    </div>
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_tag', [
                'barang'  => $model,
                'curTags' => $curTags
            ]);
            ?>
        </div>
    </div>
    <?php
    /* Disable RRP
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_harga_jual_rrp', array(
                'barang' => $model,
                'rrp' => $rrp
            ));
            ?>
        </div>
    </div>
     *
     */
    ?>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 't'
                ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class'     => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
