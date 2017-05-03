<?php
/* @var $this TagController */
/* @var $model Tag */

$this->breadcrumbs=array(
	'Tags'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Tag', 'url'=>array('index')),
	array('label'=>'Manage Tag', 'url'=>array('admin')),
);
?>

<h1>Create Tag</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>