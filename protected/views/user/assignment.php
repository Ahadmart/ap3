<?php
/* @var $this AssignmentController */
/* @var $model AuthAssignment */
//
//$this->breadcrumbs = array(
//	 'Auth Items' => array('index'),
//	 $model->name => array('view', 'id' => $model->name),
//	 'Update',
//);
$this->boxHeader['small'] = 'Assignment';
$this->boxHeader['normal'] = "User Assignment: {$user->nama}";
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->renderPartial('_assignment', [
            'user' => $user,
            'authItem' => $authItem
        ]);

        $this->renderPartial('_list_assigned', [
            'user' => $user,
            'model' => $model
        ]);
        ?>
    </div>
</div>

<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items' => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => [
                    'class' => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
