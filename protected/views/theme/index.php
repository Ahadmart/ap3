<?php
/* @var $this ThemeController */
/* @var $model Theme */

$this->breadcrumbs=array(
	'Theme'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Theme';
$this->boxHeader['normal'] = 'Theme';

$this->widget('BGridView', array(
	'id'=>'theme-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nama',
		'deskripsi',
		'updated_at',
		'updated_by',
		'created_at',
		array(
			'class'=>'BButtonColumn',
		),
	),
));

$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);