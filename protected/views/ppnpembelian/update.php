<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs=array(
	'Pembelian Ppns'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PembelianPpn', 'url'=>array('index')),
	array('label'=>'Create PembelianPpn', 'url'=>array('create')),
	array('label'=>'View PembelianPpn', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage PembelianPpn', 'url'=>array('admin')),
);
?>

<h1>Update PembelianPpn <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>