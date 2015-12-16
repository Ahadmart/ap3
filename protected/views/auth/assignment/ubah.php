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
        $this->renderPartial('_assignment', array(
            'user' => $user,
            'authItem' => $authItem
        ));

        $this->renderPartial('_list_assigned', array(
            'user' => $user,
            'model' => $model
        ));
        ?>
    </div>
</div>

<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
