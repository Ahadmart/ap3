<?php

$this->widget('BGridView', [
	'id'           => 'penerimaan-detail-grid',
	'dataProvider' => $model->search(),
	'summaryText'  => '{start}-{end} dari {count}, Total: <span class="label-total">' . $headerModel->total . '</span>',
	//filter' => $model,
	'columns'      => [
		[
			'name'  => 'namaItem',
			'value' => '$data->item->nama',
		],
		//'nomor_dokumen',
		[
			'name'  => 'nomorDokumenHutangPiutang',
			'value' => 'is_null($data->hutangPiutang) ? "" : $data->hutangPiutang->nomor',
		],
		'keterangan',
		[
			'name'              => 'jumlah',
			'value'             => 'number_format($data->jumlah, 0 ,",", ".")',
			'headerHtmlOptions' => ['class' => 'right'],
			'htmlOptions'       => ['class' => 'right'],
		],
		[
			'class'           => 'BButtonColumn',
			'deleteButtonUrl' => 'Yii::app()->controller->createUrl("hapusdetail", array("id"=>$data->id))',
			'afterDelete'     => 'function(){ $.fn.yiiGridView.update(\'hutang-piutang-grid\'); }',
		],
	],
]);
