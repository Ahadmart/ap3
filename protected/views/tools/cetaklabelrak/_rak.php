<?php

$this->widget('BGridView', array(
    'id' => 'rak-grid',
    'dataProvider' => $rak->search(),
    'filter' => $rak,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
            'header' => 'Nama <span class="ak">R</span>ak',
            'accesskey' => 'r',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('pilihrak', array('id' => $data->id)) . '" class="pilih rak">' . $data->nama . '</a>';
            },
        ),
    ),
));
