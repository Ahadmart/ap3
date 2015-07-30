<?php
/* @var $this PenerimaanitemController */
/* @var $model PenerimaanItem */

$this->breadcrumbs=array(
	'Penerimaan Item'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Penerimaan Item';
$this->boxHeader['normal'] = 'Penerimaan Item';

$this->widget('BGridView', array(
	'id'=>'penerimaan-item-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nama',
		'parent_id',
		'created_at',
		'updated_at',
		'updated_by',
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
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);