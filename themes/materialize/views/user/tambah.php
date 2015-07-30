<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
	 'User' => array('index'),
	 'Tambah',
);

$this->boxHeader['small'] = 'Tambah';
$this->boxHeader['normal'] = 'Tambah User';
?>
<div class="row">
	<div class="col s12">
		<?php $this->renderPartial('_form', array('model' => $model)); ?>
	</div>
</div>

<?php
$this->menu = array(
	 array('label' => '<i class="large mdi-action-list"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
				'class' => 'btn-floating blue',
				'accesskey' => 'i'
		  ))
);
