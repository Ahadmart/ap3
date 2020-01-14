<?php

$this->widget('BGridView', ['id' => 'item-keuangan-grid',
    'dataProvider' => $itemKeuangan->search(),
    'filter' => $itemKeuangan,
    'columns' => [
        [
            'name' => 'namaParent',
            'value' => 'isset($data->parent) ? $data->parent->nama : ""',
        ],
        ['name' => 'nama',
            'filter' => '<input name="ItemKeuangan[nama]" maxlength="100" type="text" autocomplete="off" />',
            'value' => array($this, 'renderLinkPilihItemKeu'),
            'type' => 'raw',
        ],
    ],
]);
