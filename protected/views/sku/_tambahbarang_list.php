<h4>Pilih Barang</h4>
<?php
$this->widget('BGridView', [
    'id'            => 'barang-grid',
    'dataProvider'  => $model->search(),
    'filter'        => $model,
    'itemsCssClass' => 'tabel-index',
    'columns'       => [
        // 'barcode',
        // 'nama',
        [
            'class'     => 'BDataColumn',
            'name'      => 'barcode',
            'header'    => '<span class="ak">B</span>arcode',
            'accesskey' => 'b',
            // 'autoFocus' => true,
        ],
        [
            'class'     => 'BDataColumn',
            'name'      => 'nama',
            'header'    => '<span class="ak">N</span>ama',
            'accesskey' => 'n',
            'type'      => 'raw',
            'value'     => function ($data) {
                return '<a data-id="' . $data->id . '" class="pilih barang">' . $data->nama . '</a>';
            },
        ],
        [
            'name' => 'satuan',
            'value' => '$data->satuan->nama'
        ],
    ],
]);
?>

<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>