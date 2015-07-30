<?php
/* @var $this ReturpenjualanController */
/* @var $model ReturPenjualan */

$this->breadcrumbs=array(
	'Retur Penjualan'=>array('index'),
	'Index',
);

$this->boxHeader['small'] = 'Retur Penjualan';
$this->boxHeader['normal'] = 'Retur Penjualan';

$this->widget('BGridView', array(
	'id'=>'retur-penjualan-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		  array(
				'class' => 'BDataColumn',
				'name' => 'nomor',
				'header' => '<span class="ak">N</span>omor',
				'accesskey' => 'n',
				'type' => 'raw',
				'value' => array($this, 'renderLinkToView')
		  ),
		  array(
				'class' => 'BDataColumn',
				'name' => 'tanggal',
				'header' => 'Tangga<span class="ak">l</span>',
				'accesskey' => 'l',
				'type' => 'raw',
				'value' => array($this, 'renderLinkToUbah')
		  ),
		  array(
				'name' => 'namaProfil',
				'value' => '$data->profil->nama'
		  ),
		  array(
				'name' => 'nomorHutangPiutang',
				'value' => 'isset($data->hutangPiutang) ? $data->hutangPiutang->nomor:""', 
		  ),
		  array(
				'name' => 'status',
				'value' => '$data->namaStatus',
				'filter' => $model->listStatus()
		  ),
		  array(
				'header' => 'Total',
				'value' => '$data->total',
				'htmlOptions' => array('class' => 'rata-kanan')
		  ),
		  array(
				'class' => 'BButtonColumn',
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