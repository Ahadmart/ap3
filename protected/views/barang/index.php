<?php

/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = array(
    'Barang' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Barang';
$this->boxHeader['normal'] = 'Barang';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/responsive-tables.js', CClientScript::POS_HEAD);

$this->widget('BGridView', array(
    'id' => 'barang-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'itemsCssClass' => 'item-index responsive',
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'barcode',
            'header' => '<span class="ak">B</span>arcode',
            'accesskey' => 'b',
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
            'header' => '<span class="ak">N</span>ama',
            'accesskey' => 'n',
            'type' => 'raw',
            'value' => array($this, 'renderLinkToView'),
        ),
        array(
            'name' => 'namaSatuan',
            'value' => '$data->satuan->nama'
        ),
        array(
            'name' => 'namaKategori',
            'value' => '$data->kategori->nama'
        ),
        array(
            'name' => 'namaRak',
            'value' => '$data->rak->nama'
        ),
        array(
            'name' => 'status',
            'value' => '$data->namaStatus',
            'filter' => array('1' => 'Aktif', '0' => 'Non Aktif')
        ),
        array(
            'name' => 'restock_point',
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
            'filter' => false
        ),
        array(
            'name' => 'restock_level',
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
            'filter' => false
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
