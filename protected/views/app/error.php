<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);

$this->boxHeader['small'] = 'Error ' . $code;
$this->boxHeader['normal'] = 'Error ' . $code;
?>
<div class="error">
    <?php echo CHtml::encode($message); ?>
</div>
