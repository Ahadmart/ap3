<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs=array(
	'Pembelian Ppns'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PembelianPpn', 'url'=>array('index')),
	array('label'=>'Create PembelianPpn', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#pembelian-ppn-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Pembelian Ppns</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'pembelian-ppn-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'pembelian_id',
		'no_faktur_pajak',
		'total_ppn_hitung',
		'total_ppn_faktur',
		'status',
		/*
		'updated_at',
		'updated_by',
		'created_at',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
