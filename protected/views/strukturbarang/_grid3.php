<?php

/* @var $this StrukturbarangController */
/* @var $lv3 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv3-grid',
    'dataProvider'             => $lv3->search(),
    'filter'                   => null, //$lv3,
    'selectionChanged'         => 'lv3Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv3row", "data-lv3-index" => $data->id, "data-lv3-urutan" => $data->urutan]',
//    'beforeAjaxUpdate'         => 'js:function(id,options){
//        options.data.parent_id = $("#input-tambah-lv3").attr("data-parent");
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