<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs = [
	'Pembelian Ppn' => ['index'],
	'Index',
];

$this->boxHeader['small']  = 'Pembelian Ppn';
$this->boxHeader['normal'] = 'Pembelian Ppn';

$this->widget('BGridView', [
	'id'           => 'pembelian-ppn-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => [
		// 'id',
		// 'pembelian_id',
		[
			'name' => 'pembelianNomor',
			'value' => '$data->pembelian->nomor'
		],
		'no_faktur_pajak',
		'total_ppn_hitung',
		'total_ppn_faktur',
		'status',
		/*
        'updated_at',
        'updated_by',
        'created_at',
         */
		[
			'class' => 'BButtonColumn',
		],
	],
]);

$this->menu = [
	['itemOptions' => ['class' => 'divider'], 'label' => ''],
	[
		'itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => '',
		'items'             => [
			['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class'     => 'button',
				'accesskey' => 't',
			]],
		],
		'submenuOptions'    => ['class' => 'button-group'],
	],
	[
		'itemOptions'          => ['class' => 'has-form show-for-small-only'], 'label' => '',
		'items'             => [
			['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class' => 'button',
			]],
		],
		'submenuOptions'    => ['class' => 'button-group'],
	],
];
