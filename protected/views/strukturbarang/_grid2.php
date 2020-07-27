<?php

/* @var $this StrukturbarangController */
/* @var $lv2 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv2-grid',
    'dataProvider'             => $lv2->search(),
    'filter'                   => null, //$lv2,
    'selectionChanged'         => 'lv2Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv2row", "data-lv2-index" => $data->id, "data-lv2-urutan" => $data->urutan]',
//    'beforeAjaxUpdate'         => 'js:function(id, options){
//        options.data.parent_id = $("#input-tambah-lv2").attr("data-parent");
//        }',
    'enableSorting'            => false,
    'columns'                  => [
        // 'nama',
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