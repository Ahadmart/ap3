<?php
/* @var $this CetaklabelrakController */

$this->breadcrumbs = array(
    'Cetak Label Rak' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Cetak Label';
$this->boxHeader['normal'] = 'Cetak Label Rak';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="small-12 medium-6 column">
        <div class="panel">
            <h4>Cari Barang</h4>
            <hr />
            <?php //$this->renderPartial('_config', array('modelConfig' => $modelConfig)); ?>
        </div>
    </div>
    <div class="small-12 medium-6 column">
        <div class="panel">
            <h4>Label yang akan dicetak</h4>
            <hr />

        </div>
    </div>
</div>
