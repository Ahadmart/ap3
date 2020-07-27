<?php

/* @var $this BarangController */
/* @var $lv2 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv2-grid',
    'dataProvider'             => $lv2->search(),
    'filter'                   => null, //$lv2,
    'selectionChanged'         => 'lv2Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv2row", "data-lv2-index" => $data->id, "data-lv2-urutan" => $data->urutan]',
    'enableSorting'            => false,
    'template'                 => '{items}',
    'columns'                  => [
        // 'nama',
        [
            'name'   => 'nama',
            'value'  => '$data->nama',
            'header' => 'Level 2',
        ],
    ],
]);
?>  