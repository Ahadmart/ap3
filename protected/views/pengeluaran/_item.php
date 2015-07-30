<?php

$this->widget('BGridView', array(
    'id' => 'item-keuangan-grid',
    'dataProvider' => $itemKeuangan->search(),
    'filter' => $itemKeuangan,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
            'header' => '<span class="ak">N</span>ama Item',
            'accesskey' => 'n',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('pilihitem', array('id' => $data->id)) . '" class="pilih item">' . $data->nama . '</a>';
            },
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'namaParent',
            'filter' => false,
            'header' => 'Pa<span class="ak">r</span>ent',
            'accesskey' => 'r',
            'type' => 'raw',
            'value' => '$data->parent->nama'
        )
    ),
));
