<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs = [
    'Menu' => ['index'],
    'Index',
];

$this->boxHeader['small'] = 'Menu';
$this->boxHeader['normal'] = 'Menu';
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id' => 'menu-grid',
            'dataProvider' => $model->search(),
            'filter' => null,
            'columns' => [
                [
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    //'header' => '<span class="ak">N</span>ama',
                    //'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => [$this, 'renderLinkToUbah'],
                ],
                'keterangan',
                [
                    'class' => 'BButtonColumn',
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => '',
        'items' => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                    'accesskey' => 't'
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => '',
        'items' => [
            ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
