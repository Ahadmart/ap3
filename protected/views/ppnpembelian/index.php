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
				// 'id',
				// 'pembelian_id',
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'pembelianNomor',
                    'header'    => '<span class="ak">P</span>embelian',
                    'accesskey' => 'p',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkToValidasi'],
                ],
				'no_faktur_pajak:ppnFaktur',
				'total_ppn_hitung:uang',
				'total_ppn_faktur:uang',
				// 'status',
                [
                    'name'   => 'status',
                    'value'  => '$data->namaStatus',
                    'filter' => $model->listStatus(),
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