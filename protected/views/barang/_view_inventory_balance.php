<h4>Inventory <small>Balance</small></h4>
<hr />
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
$this->widget('BGridView', array(
    'id' => 'inventory-balance-grid',
    'itemsCssClass' => 'tabel-index responsive',
    'dataProvider' => $inventoryBalance->search(),
    'columns' =>
    array(
        array(
            'name' => 'asal',
            'value' => '$data->namaAsal'
        ),
        array(
            'name' => 'nomor_dokumen',
            'type' => 'raw',
            'value' => array($this, 'renderInventoryDocumentLinkToView')
        ),
        array(
            'name' => 'created_at',
            'header' => 'Tgl',
            'value' => 'date_format(date_create_from_format(\'Y-m-d H:i:s\', $data->created_at), \'d-m-Y\')'
        ),
        array(
            'name' => 'harga_beli',
            'value' => 'number_format($data->harga_beli,0,",",".")',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
        array(
            'name' => 'qty',
            'value' => 'number_format($data->qty,0,",",".")',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
    ),
));
