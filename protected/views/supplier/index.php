<?php

/* @var $this SupplierController */
/* @var $model Profil */

$this->breadcrumbs = array(
    'Supplier' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Supplier';
$this->boxHeader['normal'] = 'Supplier';

$this->widget('BGridView', array(
    'id' => 'supplier-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
            'header' => '<span class="ak">N</span>ama',
            'accesskey' => 'n',
            'type' => 'raw',
            'value' => function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('view', array('id' => $data->id)) . '">' . $data->nama . '</a>';
            },
        ),
        'alamat1',
        'alamat2',
        'alamat3',
        'telp',
        'keterangan',
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
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
