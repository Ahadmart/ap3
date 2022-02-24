<?php
/* @var $this MembershipController */

$this->breadcrumbs = [
    'Membership' => ['index'],
    $model->nomor,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Member: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_view_member', [
                'model' => $model
            ]);
            // print_r($model);
            ?>
        </div>
    </div>
</div>

<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', ['id' => $model->nomor]), 'linkOptions' => [
                'class'     => 'button',
                'accesskey' => 'u'
            ]],
            // ['label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', ['id' => $model->nomor]), 'linkOptions' => [
            //     'class'     => 'alert button',
            //     'accesskey' => 'h',
            //     'submit'    => ['hapus', 'id' => $model->nomor],
            //     'confirm'   => 'Anda yakin?'
            // ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', ['id' => $model->nomor]), 'linkOptions' => [
                'class' => 'button',
            ]],
            // ['label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', ['id' => $model->nomor]), 'linkOptions' => [
            //     'class'   => 'alert button',
            //     'submit'  => ['hapus', 'id' => $model->nomor],
            //     'confirm' => 'Anda yakin?'
            // ]],
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                'class' => 'success button',
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
