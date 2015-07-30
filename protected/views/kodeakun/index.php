<?php

/* @var $this KodeakunController */
/* @var $model KodeAkun */

$this->breadcrumbs = array(
    'Kode Akun' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Kode Akun';
$this->boxHeader['normal'] = 'Kode Akun';

$this->widget('BGridView', array(
    'id' => 'kode-akun-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'kode',
            'header' => '<span class="ak">K</span>ode',
        ),
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
        'parent_id',
        'level',
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
