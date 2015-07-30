<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
	 'User' => array('index'),
	 $model->id => array('view', 'id' => $model->id),
	 'Update',
);

$this->boxHeader['small'] = 'Ubah';
$this->boxHeader['normal'] = "User: {$model->nama}";
?>
<div class="row">
	<div class="col s12">
		<?php $this->renderPartial('_form', array('model' => $model)); ?>
	</div>
</div>
<?php
$this->menu = array(
	 array('label' => '<i class="large mdi-content-add"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
				'class' => 'btn-floating red',
				'accesskey' => 't'
		  )),
	 array('label' => '<i class="large mdi-action-list"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
				'class' => 'btn-floating blue',
				'accesskey' => 'i'
		  ))
);
