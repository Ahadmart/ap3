<?php
/* @var $this AppController */
/* @var $model RekapAds */
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<h4>Notifikasi Potensi Lost Sales <small>[Analisa 30 hari penjualan terakhir] [Estimasi Sisa Stok < 7 hari]</small></h4>
<hr />
<?php
$this->widget('BGridView', array(
    'id' => 'rekap-ads-grid',
    'itemsCssClass' => 'tabel-index responsive',
    'dataProvider' => $model->search(),
    'filter' => null,
    'columns' => array(
        [
            'name' => 'barcode',
            'type' => 'raw',
            'value' => array($this, 'renderLinkToViewBarang'),
        ],
        [
            'name' => 'namaBarang',
            'value' => '$data->barang->nama'
        ],
        [
            'name' => 'qty',
            'value' => function($data) {
                return number_format($data->qty, 0, ',', '.');
            },
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
        ],
        [
            'name' => 'ads',
            'value' => function($data) {
                return number_format($data->ads, 4, ',', '.');
            },
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
        ],
        [
            'name' => 'stok',
            'value' => function($data) {
                return number_format($data->stok, 0, ',', '.');
            },
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
        ],
        [
            'name' => 'sisa_hari',
            'value' => function($data) {
                return number_format($data->sisa_hari, 2, ',', '.');
            },
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
        ],
    ),
));
