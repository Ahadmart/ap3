<?php
/* @var $this PesananpenjualanController */
/* @var $model PesananPenjualan */

$this->breadcrumbs = [
    'Pesanan Penjualan' => ['index'],
    $model->id,
];

$this->boxHeader['small']  = 'View';
$this->boxHeader['normal'] = 'Pesanan Penjualan: ' . $model->nomor;
?>
<div class="row">
    <div class="small-12 columns header">
        <span class="secondary label">Profil</span><span class="label"><?php echo $model->profil->nama; ?></span>
        <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
        <span class="secondary label">Total</span><span class="label"><?php echo $model->total; ?></span>
        <span class="secondary label">Status</span><span class="alert label"><?php echo $model->getNamaStatus(); ?></span>
    </div>
</div>
<div class="row">
    <div class="small-12  columns">
        <?php
        $this->widget('BGridView',
                [
            'id'            => 'sales-order-detail-grid',
            'dataProvider'  => $modelDetail->search(),
            'itemsCssClass' => 'tabel-index responsive',
            //'filter' => $penjualanDetail,
            'columns'       => [
                [
                    'name'  => 'barcode',
                    'value' => '$data->barang->barcode',
                ],
                [
                    'name'  => 'namaBarang',
                    'value' => '$data->barang->nama',
                ],
                [
                    'header'            => 'Stok',
                    'value'             => '$data->barang->stok',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    'name'              => 'qty',
                    'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
//			  array(
//					'name' => 'harga_beli',
//					'htmlOptions' => array('class' => 'rata-kanan'),
//					'value' => function($data) {
//			 return number_format($data->harga_beli, 0, ',', '.');
//		 }
//			  ),
                [
                    'name'              => 'harga_jual',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'value'             => function($data) {
                        return number_format($data->harga_jual, 0, ',', '.');
                    }
                ],
                [
                    'name'              => 'subTotal',
                    'value'             => '$data->total',
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'filter'            => false
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    [
        'itemOptions'    => ['class' => 'has-form hide-for-small-only'],
        'label'          => false,
        'items'          => [
            [
                'label'       => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah',
                'url'         => $this->createUrl('ubah', ['id' => $model->id]),
                'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 'u'
                ]
            ],
            /*
              [
              'label'       => '<i class="fa fa-times"></i> Bata<span class="ak">l</span>',
              'url'         => $this->createUrl('batal', ['id' => $model->id]),
              'linkOptions' => [
              'class'     => 'alert button',
              'accesskey' => 'l',
              'submit'    => ['batal', 'id' => $model->id],
              'confirm'   => 'Anda yakin?'
              ]
              ],
             * 
             */
            [
                'label'       => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class'     => 'success button',
                    'accesskey' => 'i'
                ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    [
        'itemOptions'    => ['class' => 'has-form show-for-small-only'],
        'label'          => false,
        'items'          => [
            [
                'label'       => '<i class="fa fa-pencil"></i>',
                'url'         => $this->createUrl('ubah', ['id' => $model->id]),
                'linkOptions' => ['class' => 'button',]
            ],
            /*
              [
              'label'       => '<i class="fa fa-times"></i>',
              'url'         => $this->createUrl('batal', ['id' => $model->id]),
              'linkOptions' => [
              'class'   => 'alert button',
              'submit'  => ['batal', 'id' => $model->id],
              'confirm' => 'Anda yakin?'
              ]
              ],
             * 
             */
            [
                'label'       => '<i class="fa fa-asterisk"></i>',
                'url'         => $this->createUrl('index'),
                'linkOptions' => [
                    'class' => 'success button',
                ]
            ]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
