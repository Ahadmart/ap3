<?php

$this->widget('BGridView', array(
    'id' => 'hutang-piutang-grid',
    'dataProvider' => $hutangPiutang->search(),
    'filter' => $hutangPiutang,
    'columns' => array(
        array(
            'name' => 'tipe',
            'value' => '$data->namaTipe',
            'filter' => $listNamaTipe
        ),
        array(
            'name' => 'asal',
            'value' => '$data->namaAsal',
            'filter' => $listNamaAsalHutangPiutang
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'namaProfil',
            'header' => 'Profi<span class="ak">l</span>',
            'value' => '$data->profil->nama',
            'accesskey' => 'l',
            'type' => 'raw',
        ),
        'nomor_dokumen_asal',
        array(
            'name' => 'noRef',
            'header' => 'Ref',
            'value' => 'is_null($data->noref)? "" : $data->noref'
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'nomor',
            'header' => 'N<span class="ak">o</span> Hutang Piutang',
            'accesskey' => 'o',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('pilihdokumen', array('id' => $data->id)) . '" class="pilih dokumen">' . $data->nomor . '</a>';
            },
                ),
                array(
                    'name' => 'sisa',
                    'header' => 'Unpaid',
                    'value' => 'number_format($data->sisa, 0, ",",".")',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false,
                ),
                array(
                    'name' => 'jumlah',
                    'value' => 'number_format($data->jumlah, 0, ",",".")',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan')
                ),
                'created_at',
            ),
        ));

