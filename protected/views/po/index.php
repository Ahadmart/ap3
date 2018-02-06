<?php
    /* @var $this PoController */
    /* @var $model Po */

    $this->breadcrumbs = [
        'PO' => ['index'],
        'Index',
    ];

    $this->boxHeader['small']  = 'PO';
    $this->boxHeader['normal'] = 'PO';
?>
	<div class="row" style="overflow: auto">
		<div class="small-12 columns">
			<?php
                $this->widget('BGridView', [
                    'id'           => 'po-grid',
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
                        'profil_id',
                        'referensi',
                        'tanggal_referensi',
                        /*
                        'status',
                        'pembelian_id',
                        'updated_at',
                        'updated_by',
                        'created_at',
                         */
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
            ['itemOptions'   => ['class' => 'has-form hide-for-small-only'], 'label' => '',
                'items'          => [
                    ['label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                        'class'     => 'button',
                        'accesskey' => 't',
                    ]],
                ],
                'submenuOptions' => ['class' => 'button-group'],
            ],
            ['itemOptions'   => ['class' => 'has-form show-for-small-only'], 'label' => '',
                'items'          => [
                    ['label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => [
                        'class' => 'button',
                    ]],
                ],
                'submenuOptions' => ['class' => 'button-group'],
        ],
    ];