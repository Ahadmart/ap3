<?php

$this->widget('BGridView', ['id' => 'item-keuangan-grid',
    'dataProvider' => $itemKeuangan->search(),
    'filter' => $itemKeuangan,
    'columns' => [
        [
            'name' => 'parent_id',
            'filter' => false
        ],
        ['name' => 'nama',
            'filter' => '<input name="ItemKeuangan[nama]" maxlength="100" type="text" autocomplete="off" />',
            'value' => array($this, 'renderLinkPilihItemKeu'),
            'type' => 'raw',
        ],
    ],
]);
