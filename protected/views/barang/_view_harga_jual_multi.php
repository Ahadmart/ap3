<h4>Multi Harga <small>Jual</small></h4>
<hr />
<?php
$this->widget('BGridView', [
    'id'           => 'harga-jual-multi-grid',
    'dataProvider' => $hargaJualMulti->search(),
    'columns'      => [
        [
            'name'  => 'satuan',
            'value' => '$data->satuan->nama',
        ],
        'qty',
        [
            'name'              => 'harga',
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan']
        ],
        [
            'name' => 'created_at',
        ],
    ],
]);
