<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs=array(
	'Pembelian Ppns'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PembelianPpn', 'url'=>array('index')),
	array('label'=>'Manage PembelianPpn', 'url'=>array('admin')),
);
?>

<h1>Create PembelianPpn</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>