<?php

$this->widget('BGridView', array(
	 'id' => 'pengeluaran-detail-grid',
	 'dataProvider' => $model->search(),
	 'summaryText' => '{start}-{end} dari {count}, Total: '.$headerModel->total,
	 //filter' => $model,
	 'columns' => array(
		  array(
				'name' => 'namaItem',
				'value' => '$data->item->nama'
		  ),
		  //'nomor_dokumen',
		  array(
				'name' => 'nomorDokumenHutangPiutang',
				'value' => 'is_null($data->hutangPiutang) ? "" : $data->hutangPiutang->nomor'
		  ),
		  'keterangan',
		  array(
				'name' => 'jumlah',
            'value' => 'number_format($data->jumlah, 0 ,",", ".")',
				'headerHtmlOptions' => array('class' => 'right'),
				'htmlOptions' => array('class' => 'right')
		  ),
		  array(
				'class' => 'BButtonColumn',
				'deleteButtonUrl' => 'Yii::app()->controller->createUrl("hapusdetail", array("id"=>$data->id))',
				'afterDelete' => 'function(){ $.fn.yiiGridView.update(\'hutang-piutang-grid\'); }'
		  ),
	 ),
));
