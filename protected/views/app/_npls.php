<?php
/* @var $this AppController */
/* @var $model RekapAds */
?>
<h4>Notifikasi Potensi Lost Sales <small>[Analisa 30 hari penjualan terakhir] [Periode Supplier: 7 hari]</small></h4>
<hr />
<?php
$this->widget('BGridView', array(
    'id' => 'rekap-ads-grid',
    'dataProvider' => $model->search(),
    'filter' => null,
    'columns' => array(
        [
            'name' => 'barcode',
            'value' => '$data->barang->barcode'
        ],
        [
            'name' => 'namaBarang',
            'value' => '$data->barang->nama'
        ],
        'qty',
        'ads',
        'stok',
        'sisa_hari',
        //'updated_at',
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));
