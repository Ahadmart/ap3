<?php

/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

$this->breadcrumbs = array(
    'Diskon Barang' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Diskon Barang';
$this->boxHeader['normal'] = 'Diskon Barang';

$this->widget('BGridView', array(
    'id' => 'diskon-barang-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        //'id',
        'barang_id',
        'tipe_diskon_id',
        'nominal',
        'persen',
        'dari',
        'sampai',
        'qty',
        'qty_min',
        'qty_max',
        'status',
        /*
          'updated_at',
          'updated_by',
          'created_at',
         */
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
