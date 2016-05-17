<?php
/* @var $this AuthitemController */
/* @var $model AuthItem */

$this->breadcrumbs = array(
    'Item Otorisasi' => array('index'),
    $model->name => array('view', 'id' => $model->name),
    'Ubah',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "Item Otorisasi: {$model->name}";
?>
<div class="row">
    <div class="large-6 columns">
        <div class="panel">
            <?php $this->renderPartial('_form', array('model' => $model)); ?>
        </div>
    </div>
    <div class="large-6 columns">
        <div class="panel">
            <?php
            $this->renderPartial('_child', array(
                'model' => $child,
                'id' => $model->name,
                'authItem' => $authItem,
            ));
            ?>
        </div>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button'
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
