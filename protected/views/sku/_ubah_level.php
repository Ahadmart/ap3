<?php
/* @var $this SkuController */
/* @var $modelLevel SkuLevel */

$this->widget('BGridView', [
    'id'           => 'sku-level-grid',
    'dataProvider' => $modelLevel->search(),
    // 'filter' => $modelLevel,
    'columns'      => [
        'level',
        [
            'name'  => 'namaSatuan',
            'value' => '$data->satuan->nama',
        ],
        [

            'name'  => 'rasio_konversi',
            'type'  => 'raw',
            'value' => [$this, 'renderRasioKonversi'],
        ],
        [
            'class'           => 'BButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("hapuslevel", ["id"=>$data->primaryKey])',
            'buttons'         => [
                'delete' => [
                    'visible' => '$data->level == ' . $levelMax,
                ],
            ],
        ],
        /*
    'updated_at',
    'updated_by',
    'created_at',
     */
    ],
]);
