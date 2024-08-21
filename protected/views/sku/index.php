<?php
/* @var $this SkuController */
/* @var $model Sku */

$this->breadcrumbs = [
	'SKU' => ['index'],
	'Index',
];

$this->boxHeader['small']  = 'SKU';
$this->boxHeader['normal'] = 'SKU';
?>
<div class="row">
	<div class="small-12 columns">
		<?php
		$this->widget('BGridView', [
			'id'           => 'sku-grid',
			'dataProvider' => $model->search(),
			'filter'       => $model,
			'columns'      => [
				'nomor',
				[
					'class'     => 'BDataColumn',
					'name'      => 'nama',
					'header'    => '<span class="ak">N</span>ama',
					'accesskey' => 'n',
					'type'      => 'raw',
					'value'     => [$this, 'renderLinkToView'],
				],
				'kategori_id',
				'struktur_id',
				[
					'name'   => 'status',
					'value'  => '$data->namaStatus',
					'filter' => $model->filterStatus(),
				],
				[
					'class' => 'BButtonColumn',
				],
			],
		]);
		?>
	</div>
</div>
<?php
$this->menu = [
	['itemOptions' => ['class' => 'divider'], 'label' => ''],
	[
		'itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label' => '',
		'items'          => [
			['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class'     => 'button',
				'accesskey' => 't',
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
	[
		'itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label' => '',
		'items'          => [
			['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
				'class' => 'button',
			]],
		],
		'submenuOptions' => ['class' => 'button-group'],
	],
];
