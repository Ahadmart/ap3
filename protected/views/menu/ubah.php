<?php
/* @var $this MenuController */
/* @var $model Menu */

$this->breadcrumbs = [
    'Menu' => ['index'],
    $model->id => ['view', 'id' => $model->id],
    'Ubah',
];

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Menu: {$model->nama}";
?>
<div class="row">
    <div class="medium-6 large-4 columns">
        <div class="panel">
            <h4>Menu</h4>
            <hr />
            <?php
            $this->renderPartial('_form', [
                'model' => $model,
            ]);
            ?>
        </div>
    </div>
    <div class="medium-6 large-8 columns">
        <div class="panel">
            <h4>Sub Menu</h4>
            <hr />
            <?php
            $this->renderPartial('_form_detail', [
                'rootMenu' => $model,
                'model' => $subMenuModel,
                'subMenuList' => $subMenuList
            ]);
            ?>
        </div>
    </div>
</div>
<div id="menu-preview">
    <?php
    $this->renderPartial('_menu_preview', [
        'rootMenu' => $model,
        'subMenuTreeList' => $subMenuTreeList,
    ]);
    ?>
</div>
<div id="menu-detail">
    <?php
    $this->renderPartial('_menu_detail', [
        'rootMenu' => $model,
        'model' => $subMenuGrid,
    ]);
    ?>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                    'accesskey' => 't'
                ]],
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items' => [
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
