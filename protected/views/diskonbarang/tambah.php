<?php

/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

$this->breadcrumbs = array(
    'Diskon Barang' => array('index'),
    'Tambah',
);

$this->boxHeader['small'] = 'Tambah';
$this->boxHeader['normal'] = 'Tambah Diskon Barang';

$this->renderPartial('_form', array(
    'model' => $model,
    'lv1' => $lv1,
    'strukturDummy' => $strukturDummy,
));

$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
