   <?php

   $this->widget('BGridView', array(
       'id' => 'penjualan-detail-grid',
       'dataProvider' => $penjualanDetail->search(),
       //'filter' => $penjualanDetail,
//       'summaryText' => false,
       'enableSorting' => false,
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
               'name' => 'qty',
               'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
           ),
           array(
               'name' => 'harga_jual',
               'headerHtmlOptions' => array('class' => 'rata-kanan'),
               'htmlOptions' => array('class' => 'rata-kanan'),
               'value' => function($data) {
          return number_format($data->harga_jual, 0, ',', '.');
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
               'template' => $penjualan->status == Penjualan::STATUS_DRAFT ? '{delete}' : '',
               'deleteButtonUrl' => 'Yii::app()->controller->createUrl("penjualan/hapusdetail", array("id"=>$data->primaryKey))',
               'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}',
           ),
       ),
   ));
   