<?php

/* @var $this BarangController */
/* @var $lv1 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv1-grid',
    'dataProvider'             => $lv1->search(),
    'filter'                   => null, //$lv1,
    'selectionChanged'         => 'lv1Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv1row", "data-lv1-index" => $data->id, "data-lv1-urutan" => $data->urutan]',
    'enableSorting'            => false,
    'template'                 => '{items}',
    'columns'                  => [
        // 'nama',
        [
            'name'   => 'nama',
            'value'  => '$data->nama',
            'header' => 'Level 1',
        ],
    ],
]);
?>  