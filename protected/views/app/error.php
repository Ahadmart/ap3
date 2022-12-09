<?php
/* @var $this AppController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);

$this->boxHeader['small'] = 'Error ' . $code;
$this->boxHeader['normal'] = 'Error ' . $code;
?>
<div class="error alone">
    <?php //echo CHtml::encode($message); ?>
    <?php echo $message ?>
</div>
