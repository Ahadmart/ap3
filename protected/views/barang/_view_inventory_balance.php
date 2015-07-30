<h4>Inventory <small>Balance</small></h4>
<hr />
<?php
$this->widget('BGridView', array(
	 'id' => 'inventory-balance-grid',
	 'dataProvider' => $inventoryBalance->search(),
	 'columns' =>
	 array(
		  array(
				'name' => 'asal',
				'value' => '$data->namaAsal'
		  ),
		  array(
				'name' => 'nomor_dokumen',
				'type' => 'raw',
				'value' => array($this, 'renderInventoryDocumentLinkToView')
		  ),
		  array(
				'name' => 'harga_beli',
				'value' => 'number_format($data->harga_beli,0,",",".")',
				'headerHtmlOptions' => array('class' => 'rata-kanan'),
				'htmlOptions' => array('class' => 'rata-kanan')
		  ),
		  array(
				'name' => 'qty',
				'value' => 'number_format($data->qty,0,",",".")',
				'headerHtmlOptions' => array('class' => 'rata-kanan'),
				'htmlOptions' => array('class' => 'rata-kanan')
		  ),
	 ),
));
