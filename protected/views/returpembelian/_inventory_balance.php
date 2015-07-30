<?php
$this->widget('BGridView', array(
	 'id' => 'inventory-balance-grid',
	 'dataProvider' => $inventoryBalance->search('t.id'), // order by id asc
	 'enableSorting' => false,
	 'emptyText' => 'Stok kosong',
	 'columns' => array(
		  'asal',
		  'nomor_dokumen',
		  'harga_beli',
		  'qty',
//		  array(
//				'class' => 'BButtonColumn',
//				'htmlOptions' => array('style' => 'text-align:center'),
//				// Pakai template delete untuk pilih :) biar gampang
//				'deleteButtonUrl' => 'Yii::app()->createUrl("returpembelian/pilihinv", array("id"=>$data->id,"rpId"=>'.$model->id.'))',
//				'afterDelete' => 'function(link,success,data){ if(success){ $.fn.yiiGridView.update("retur-pembelian-detail-grid");updateTotal();}}',
//				'deleteButtonImageUrl' => false,
//				'deleteButtonLabel' => '<i class="fa fa-check"></i>',
//				'deleteButtonOptions' => array('title' => 'Pilih', 'class' => 'pilih'),
//				'deleteConfirmation' => false,
//		  ),
		  array(
				'header' => 'Pilih',
				'type' => 'raw',
				'value' => array($this, 'renderRadioButton')
		  )
	 ),
));
?>
<script>
	$("body").on("focusin", "a.pilih", function () {
		$(this).parent('td').parent('tr').addClass('pilih');
	});

	$("body").on("focusout", "a.pilih", function () {
		$(this).parent('td').parent('tr').removeClass('pilih');
	});
</script>