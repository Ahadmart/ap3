<?php
/* @var $this AkmController */

$this->breadcrumbs = array(
    'AKM',
);


$this->boxHeader['small'] = 'Anjungan Kasir Mandiri';
$this->boxHeader['normal'] = 'AKM';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/pos.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/akm.css');
?>

<div id="content">
    <?php
    $this->renderPartial('_input', [
        'model' => $model,
        'akmDetail' => $akmDetail
    ]);
    ?>
</div>
