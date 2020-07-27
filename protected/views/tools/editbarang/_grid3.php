<?php

/* @var $this EditBarangController */
/* @var $lv3 StrukturBarang */

$this->widget('BGridView', [
    'id'                       => 'lv3-grid',
    'dataProvider'             => $lv3->search(),
    'filter'                   => null, //$lv3,
    'selectionChanged'         => 'lv3Dipilih',
    'rowHtmlOptionsExpression' => '["class"=>"lv3row", "data-lv3-index" => $data->id, "data-lv3-urutan" => $data->urutan]',
    'enableSorting'            => false,
    'template'                 => '{items}',
    'columns'                  => [
        // 'nama',
        [
            'name'   => 'nama',
            'value'  => '$data->nama',
            'header' => 'Level 3',
        ],
    ],
]);
?>  