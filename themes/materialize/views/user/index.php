<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
	 'User' => array('index'),
	 'Index',
);

$this->boxHeader['small'] = 'User';
$this->boxHeader['normal'] = 'User';
?>
<div class="row">
	<div class="col s12">
		<?php
		$this->widget('MGridView', array(
			 'id' => 'user-grid',
			 'dataProvider' => $model->search(),
			 'filter' => $model,
			 'columns' => array(
				  array(
						'class' => 'BDataColumn',
						'name' => 'nama',
						'header' => '<span class="ak">N</span>ama',
						'accesskey' => 'n',
						'type' => 'raw',
						'value' => function($data) {
							return '<a href="'.Yii::app()->controller->createUrl('view', array('id' => $data->id)).'">'.$data->nama.'</a>';
						}
							 ),
							 'nama_lengkap',
							 array(
								  'class' => 'BButtonColumn',
							 ),
						),
				  ));
				  ?>
		  	</div>
		  </div>
		  <?php
		  $this->menu = array(
				array('label' => '<i class="large mdi-content-add"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
						  'class' => 'btn-floating red',
						  'accesskey' => 't'
					 )),
		  );
		  