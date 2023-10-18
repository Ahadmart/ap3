<?php
/* @var $this PengeluaranController */
/* @var $model Pengeluaran */

$this->breadcrumbs = [
    'Pengeluaran' => ['index'],
    $model->id    => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small']  = 'Ubah';
$this->boxHeader['normal'] = "Pengeluaran: {$model->nomor}";
?>
<div class="row">
    <div class="large-12 columns">
        <?php
        echo CHtml::ajaxLink(
            '<i class="fa fa-gears"></i> P<span class="ak">r</span>oses',
            $this->createUrl('proses', ['id' => $model->id]),
            [
                'data'    => 'proses=true',
                'type'    => 'POST',
                'success' => 'function(data) {
                            if (data.sukses) {
                                location.reload();
                            }
                        }',
            ],
            [
                'class'     => 'tiny bigfont button',
                'accesskey' => 'r',
            ]
        );
        ?>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_form', [
                'model'  => $model,
                'profil' => $profil,
            ]);
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_form_detail', [
                'model'                     => $pengeluaranDetail,
                'itemKeuangan'              => $itemKeuangan,
                'hutangPiutang'             => $hutangPiutang,
                'listNamaAsalHutangPiutang' => $listNamaAsalHutangPiutang,
                'listNamaTipe'              => $listNamaTipe,
                'headerModel'               => $model,
            ]);
            ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_detail', [
                'model'       => $detail,
                'headerModel' => $model,
            ]);
            ?>
        </div>
    </div>
</div>

<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'             => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 't',
            ]],
            ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'     => 'alert button',
                'accesskey' => 'h',
                'submit'    => ['hapus', 'id' => $model->id],
                'confirm'   => 'Anda yakin?',
            ]],
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
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                'class' => 'button',
            ]],
            ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->id]), 'linkOptions' => [
                'class'   => 'alert button',
                'submit'  => ['hapus', 'id' => $model->id],
                'confirm' => 'Anda yakin?',
            ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]],
        ],
        'submenuOptions'    => ['class' => 'button-group'],
    ],
];
?>