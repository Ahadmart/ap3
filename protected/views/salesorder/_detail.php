<div class="small-12  columns">
    <?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js',
            CClientScript::POS_HEAD);

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
            // Jika penjualan masih draft tampilkan tombol hapus
            [
                'class'           => 'BButtonColumn',
                //'template'        => $penjualan->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("pesananpenjualan/hapusdetail", ["id"=>$data->primaryKey])',
                'afterDelete'     => 'function(link,success,data){ if(success) updateTotal();}',
            ],
        ],
    ]);
    ?>
</div>