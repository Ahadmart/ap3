<h4><small>Daftar</small> Supplier</h4>
<hr />
<?php

$this->widget('BGridView', array(
    'id' => 'supplier-barang-grid',
    'dataProvider' => $supplierBarang->search(),
    //'filter' => $model,
    'columns' =>
    array(
        array(
            'name' => 'namaSupplier',
            'header' => 'Supplier',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->createUrl('/supplier/view', array('id' => $data->supplier_id)) . '">' . $data->supplier->nama . '</a>';
            },
        ),
        array(
            'name' => 'default',
            'headerHtmlOptions' => array('style'=> 'width:50px; text-align:center'),
            'htmlOptions' => array('style' => 'text-align:center'),
            'type' => 'raw',
            'value' => function($data) {
                $return = '<i class="fa fa-check-square"><i>';
                if ($data->default == 0) {
                    $return = '';
                }
                return $return;
            },
        ),
    ),
));
