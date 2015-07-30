<?php

/* @var $this StockopnameController */
/* @var $model StockOpname */

$this->breadcrumbs = array(
    'Stock Opname' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Stock Opname';
$this->boxHeader['normal'] = 'Stock Opname';

$this->widget('BGridView', array(
    'id' => 'stock-opname-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nomor',
            'header' => '<span class="ak">N</span>omor',
            'accesskey' => 'n',
            'type' => 'raw',
            'value' => array($this, 'renderLinkToView')
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'tanggal',
            'header' => 'Tangga<span class="ak">l</span>',
            'accesskey' => 'l',
            'type' => 'raw',
            'value' => array($this, 'renderLinkToUbah')
        ),
        array(
            'name' => 'rak_id',
            'value' => '$data->namaRak',
            'filter' => $model->listRak()
        ),
        'keterangan',
        array(
            'name' => 'status',
            'value' => '$data->namaStatus',
            'filter' => $model->listStatus()
        ),
        array(
            'class' => 'BButtonColumn',
        ),
    ),
));

$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
