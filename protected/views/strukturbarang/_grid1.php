<?php

/* @var $this StrukturbarangController */
/* @var $lv1 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv1-grid',
    'dataProvider'             => $lv1->search(),
    'filter'                   => null, //$lv1,
    'selectionChanged'         => 'lv1Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv1row", "data-lv1-index" => $data->id, "data-lv1-urutan" => $data->urutan]',
    'enableSorting'            => false,
    'columns'                  => [
        //'nama',
        [
            'name'  => 'nama',
            'value' => function($data) {
                return '<a href="#" class="editable-nama" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updatenama') . '">' .
                        $data->nama . '</a>';
            },
            'type' => 'raw',
        ],
    ],
]);
?>  