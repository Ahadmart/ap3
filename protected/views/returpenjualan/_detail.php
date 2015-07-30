<div class="small-12  columns">
   <?php
   $this->widget('BGridView', array(
       'id' => 'retur-penjualan-detail-grid',
       'dataProvider' => $returPenjualanDetail->search(),
       //'filter' => $penjualanDetail,
       'enableSorting' => false,
       'columns' => array(
           array(
               'name' => 'barcode',
               'value' => '$data->penjualanDetail->barang->barcode',
           ),
           array(
               'name' => 'namaBarang',
               'value' => '$data->penjualanDetail->barang->nama',
           ),
           array(
               'header' => 'Penjualan',
               'value' => '$data->penjualanDetail->penjualan->nomor',
           ),
           array(
               'header' => 'Tanggal Penjualan',
               'value' => '$data->penjualanDetail->penjualan->tanggal',
           ),
           array(
               'header' => 'Harga Jual',
               'value' => 'number_format($data->penjualanDetail->harga_jual,0,",",".")',
               'headerHtmlOptions' => array('class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan')
           ),
           array(
               'name' => 'qty',
               'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
           ),
			  array(
					'name' => 'subTotal',
					'value' => 'number_format($data->total,0,",",".")',
					'headerHtmlOptions' => array('class' => 'rata-kanan'),
					'htmlOptions' => array('class' => 'rata-kanan'),
					'filter' => false
			  ),
           // Jika masih draft tampilkan tombol hapus
           array(
               'class' => 'BButtonColumn',
               'template' => $returPenjualan->status == 0 ? '{delete}' : '',
               'deleteButtonUrl' => 'Yii::app()->controller->createUrl("returPenjualan/hapusdetail", array("id"=>$data->primaryKey))',
               'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
           ),
       ),
   ));
   ?>
</div>