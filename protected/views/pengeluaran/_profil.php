<?php

$this->widget('BGridView', array(
    'id' => 'profil-grid',
    'dataProvider' => $profil->search(),
    'filter' => $profil,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
            'header' => 'Nama Pro<span class="ak">f</span>il',
            'accesskey' => 'f',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('pilihprofil', array('id' => $data->id)) . '" class="pilih profil">' . $data->nama . '</a>';
            },
        ),
        array(
            'name' => 'alamat1',
            'filter' => false
        ),
        array(
            'name' => 'alamat2',
            'filter' => false
        ),
        array(
            'name' => 'alamat3',
            'filter' => false
        ),
        array(
            'name' => 'telp',
            'filter' => false
        ),
        array(
            'name' => 'keterangan',
            'filter' => false
        ),
    ),
));
