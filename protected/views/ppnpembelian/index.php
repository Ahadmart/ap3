<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs = [
	'Pembelian Ppn' => ['index'],
	'Index',
];

$this->boxHeader['small']  = 'Pembelian Ppn';
$this->boxHeader['normal'] = 'Pembelian Ppn';
?>

<div class="row" style="overflow: auto">
	<div class="small-12 columns">
		<?php
		$this->widget('BGridView', [
			'id'           => 'pembelian-ppn-grid',
			'dataProvider' => $model->search(),
			'filter'       => $model,
			'columns'      => [
				[
					'class'     => 'BDataColumn',
					'name'      => 'pembelianNomor',
					'header'    => '<span class="ak">P</span>embelian',
					'accesskey' => 'p',
					'type'      => 'raw',
					'value'     => [$this, 'renderLinkToValidasi'],
				],
				'no_faktur_pajak:ppnFaktur',
				[
					'name'              => 'total_ppn_hitung',
					'type'              => 'uang',
					'htmlOptions'       => ['class' => 'rata-kanan'],
					'headerHtmlOptions' => ['class' => 'rata-kanan'],
				],
				[
					'name'              => 'total_ppn_faktur',
					'type'              => 'uang',
					'htmlOptions'       => ['class' => 'rata-kanan'],
					'headerHtmlOptions' => ['class' => 'rata-kanan'],
				],
				[
					'name'   => 'status',
					'value'  => '$data->namaStatus',
					'filter' => $model->listStatus(),
				],
				[
					'name'  => 'namaUpdatedBy',
					'value' => '$data->updatedBy->nama_lengkap',
				],
				/*
    'updated_at',
    'updated_by',
    'created_at',
    [
    'class' => 'BButtonColumn',
    ],
     */
			],
		]);
		?>
	</div>
</div>
<?php

// $this->menu = [
//     ['itemOptions' => ['class' => 'divider'], 'label' => ''],
//     [
//         'itemOptions'          => ['class' => 'has-form hide-for-small-only'], 'label' => '',
//         'items'             => [
//             ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
//                 'class'     => 'button',
//                 'accesskey' => 't',
//             ]],
//         ],
//         'submenuOptions'    => ['class' => 'button-group'],
//     ],
//     [
//         'itemOptions'          => ['class' => 'has-form show-for-small-only'], 'label' => '',
//         'items'             => [
//             ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
//                 'class' => 'button',
//             ]],
//         ],
//         'submenuOptions'    => ['class' => 'button-group'],
//     ],
// ];
$this->menu = [];
?>