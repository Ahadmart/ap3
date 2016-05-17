<div class="small-12  columns">
    <?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

    $this->widget('BGridView', array(
        'id' => 'penjualan-detail-grid',
        'dataProvider' => $penjualanDetail->search(),
        'itemsCssClass' => 'tabel-index responsive',
        //'filter' => $penjualanDetail,
        'columns' => array(
            array(
                'name' => 'barcode',
                'value' => '$data->barang->barcode',
            ),
            array(
                'name' => 'namaBarang',
                'value' => '$data->barang->nama',
            ),
            array(
                'header' => 'Stok',
                'value' => '$data->barang->stok',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
            ),
            array(
                'name' => 'qty',
                'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
            ),
//			  array(
//					'name' => 'harga_beli',
//					'htmlOptions' => array('class' => 'rata-kanan'),
//					'value' => function($data) {
//			 return number_format($data->harga_beli, 0, ',', '.');
//		 }
//			  ),
            array(
                'name' => 'harga_jual',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'value' => function($data) {
            return number_format($data->harga_jual, 0, ',', '.');
        }
            ),
            array(
                'name' => 'harga_jual_rekomendasi',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'value' => function($data) {
            return number_format($data->harga_jual_rekomendasi, 0, ',', '.');
        }
            ),
            array(
                'name' => 'subTotal',
                'value' => '$data->total',
                'headerHtmlOptions' => array('class' => 'rata-kanan'),
                'htmlOptions' => array('class' => 'rata-kanan'),
                'filter' => false
            ),
            // Jika penjualan masih draft tampilkan tombol hapus
            array(
                'class' => 'BButtonColumn',
                'template' => $penjualan->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("penjualan/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
            ),
        ),
    ));
    ?>
</div>