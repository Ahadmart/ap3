<?php
/* @var $this SkutransferController */
/* @var $model SkuTransfer */

$this->breadcrumbs = [
	'Sku Transfer' => ['index'],
	'Index',
];

$this->boxHeader['small']  = 'Sku Transfer';
$this->boxHeader['normal'] = 'Sku Transfer';

$this->widget('BGridView', [
	'id'           => 'sku-transfer-grid',
	'dataProvider' => $model->search(),
	'filter'       => $model,
	'columns'      => [
		[
			'class'     => 'BDataColumn',
			'name'      => 'nomor',
			'header'    => '<span class="ak">N</span>omor',
			'accesskey' => 'n',
			'type'      => 'raw',
			'value'     => [$this, 'renderLinkToView'],
		],
		[
			'class'     => 'BDataColumn',
			'name'      => 'tanggal',
			'header'    => 'Tangga<span class="ak">l</span>',
			'accesskey' => 'l',
			'type'      => 'raw',
			'value'     => [$this, 'renderLinkToUbah'],
		],
		[
			'name'  => 'skuNomor',
			'value' => '$data->sku->nomor',
		],
		[
			'name'  => 'skuNama',
			'value' => '$data->sku->nama',
		],
		'referensi',
		'tanggal_referensi',
		'keterangan',
		[
			'class'   => 'BButtonColumn',
			'buttons' => [
				'delete' => [
					'visible' => '$data->status == ' . SkuTransfer::STATUS_DRAFT,
				],
			],
		],
	],
]);

$this->menu = [
	['itemOptions' => ['class' => 'divider'], 'label' => ''],
	[
		'itemOptions'    => ['class' => 'has-form hide-for-small-only'],
		'label'          => '',
		'items'          => [
			['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class'     => 'button',
				'accesskey' => 't',
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
	[
		'itemOptions'    => ['class' => 'has-form show-for-small-only'],
		'label'          => '',
		'items'          => [
			['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class' => 'button',
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
];
