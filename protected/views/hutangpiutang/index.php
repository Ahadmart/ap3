<?php
/* @var $this HutangpiutangController */
/* @var $model HutangPiutang */

$this->breadcrumbs=array(
	'Hutang Piutang'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Hutang Piutang';
$this->boxHeader['normal'] = 'Hutang Piutang';

$this->widget('BGridView', array(
	'id'=>'hutang-piutang-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'nomor',
		'jumlah',
		'tipe',
		'status',
		'asal',
		/*
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