<?php
/* @var $this PengeluarandetailController */
/* @var $model PengeluaranDetail */

$this->breadcrumbs=array(
	'Pengeluaran Detail'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Pengeluaran Detail';
$this->boxHeader['normal'] = 'Pengeluaran Detail';

$this->widget('BGridView', array(
	'id'=>'pengeluaran-detail-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'pengeluaran_id',
		'akun_id',
		'nomor_dokumen',
		'keterangan',
		'jumlah',
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