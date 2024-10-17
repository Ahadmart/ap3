<?php
/* @var $this SkutransferController */
/* @var $model SkuTransfer */

$this->breadcrumbs=array(
	'Sku Transfer'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Sku Transfer';
$this->boxHeader['normal'] = 'Sku Transfer';

$this->widget('BGridView', array(
	'id'=>'sku-transfer-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'tanggal',
		'nomor',
		'referensi',
		'tanggal_referensi',
		'sku_id',
		/*
		'keterangan',
		'status',
		'updated_at',
		'updated_by',
		'created_at',
		*/
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