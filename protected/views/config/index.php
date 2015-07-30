<?php

/* @var $this ConfigController */
/* @var $model Config */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/jquery-editable.css');
/*
 * 	Menambahkan rutin pada saat edit qty
 * 1. Update Grid Pembelian detail
 * 2. Update Total Pembelian
 */
Yii::app()->clientScript->registerScript('editableQty', ''
        .'$( document ).ajaxComplete(function() {'
        .'$(".editable-nilai").editable({'
        .'	success: function(response, newValue) {'
        .'					if (response.sukses) {'
        .'						$.fn.yiiGridView.update("config-grid");'
        .'					}'
        .'				}  '
        .'});'
        .'});'
        .'$(".editable-nilai").editable({'
        .'	success: function(response, newValue) {'
        .'					if (response.sukses) {'
        .'						$.fn.yiiGridView.update("config-grid");'
        .'					}'
        .'				}  '
        .'});', CClientScript::POS_END);

$this->breadcrumbs = array(
    'Config' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Konfigurasi';
$this->boxHeader['normal'] = 'Konfigurasi Aplikasi';

$this->widget('BGridView', array(
    'id' => 'config-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        // 'nama',
        array(
            'class' => 'BDataColumn',
            'name' => 'deskripsi',
            'header' => '<span class="ak">D</span>eskripsi Nama',
            'accesskey' => 'd',
        ),
        array(
            'name' => 'nilai',
            'value' => array($this, 'renderEditableNilai'),
            'type' => 'raw',
            'class' => 'BDataColumn',
            'header' => '<span class="ak">N</span>ilai',
            'accesskey' => 'n',
        ),
    /*
      array(
      'class' => 'BButtonColumn',
      ),
     */
    ),
));

$this->menu = array(
//    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
//    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
//        'items' => array(
//            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
//                    'class' => 'button',
//                    'accesskey' => 't'
//                )),
//        ),
//        'submenuOptions' => array('class' => 'button-group')
//    ),
//    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
//        'items' => array(
//            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
//                    'class' => 'button',
//                )),
//        ),
//        'submenuOptions' => array('class' => 'button-group')
//    )
);
