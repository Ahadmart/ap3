<?php
/* @var $this KasBankController */
/* @var $model KasBank */

$this->breadcrumbs=array(
	'Kas Bank'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Kas Bank';
$this->boxHeader['normal'] = 'Kas Bank';

$this->widget('BGridView', array(
	'id'=>'kas-bank-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nama',
		'kode_akun_id',
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
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);