<?php
/* @var $this SkuController */
/* @var $modelLevel SkuLevel */

$this->widget('BGridView', array(
    'id' => 'sku-level-grid',
    'dataProvider' => $modelLevel->search(),
    // 'filter' => $modelLevel,
    'columns' => array(
        'level',
        [
            'name'   => 'namaSatuan',
            'value'  => '$data->satuan->nama',
        ],
        'rasio_konversi',
        /*
        'updated_at',
		'updated_by',
		'created_at',
		*/
    ),
));
