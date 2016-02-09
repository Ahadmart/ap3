
<?php

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
/*
 * Menambahkan rutin pada saat edit value
 * 1. Update Grid Config
 */
Yii::app()->clientScript->registerScript('editableQty', ''
        . '$( document ).ajaxComplete(function() {'
        . '$(".editable-nilai").editable({'
        . '	success: function(response, newValue) {'
        . '					if (response.sukses) {'
        . '						$.fn.yiiGridView.update("config-grid");'
        . '					}'
        . '				}  '
        . '});'
        . '});'
        . '$(".editable-nilai").editable({'
        . '	success: function(response, newValue) {'
        . '					if (response.sukses) {'
        . '						$.fn.yiiGridView.update("config-grid");'
        . '					}'
        . '				}  '
        . '});', CClientScript::POS_END);

$this->widget('BGridView', array(
    'id' => 'config-grid',
    'dataProvider' => $modelConfig->search(),
    'itemsCssClass' => 'tabel-index responsive',
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'nama',
//            'header' => '<span class="ak">N</span>ama',
//            'accesskey' => 'n',
        ),
        array(
            'class' => 'BDataColumn',
            'name' => 'deskripsi',
            'accesskey' => 'd',
        ),
        array(
            'name' => 'nilai',
            'value' => array($this, 'renderEditableNilai'),
            'type' => 'raw',
            'class' => 'BDataColumn',
//            'header' => 'Ni<span class="ak">l</span>ai',
//            'accesskey' => 'l',
        )
    ),
));
