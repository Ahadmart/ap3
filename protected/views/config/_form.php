<?php
/* @var $this ConfigController */
/* @var $model Config */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,'Error: Perbaiki input',null,array('class'=>'panel callout')); ?>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'nama'); ?>
				<?php echo $form->textField($model,'nama',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'nama', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'nilai'); ?>
				<?php echo $form->textField($model,'nilai',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'nilai', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'deskripsi'); ?>
				<?php echo $form->textField($model,'deskripsi',array('size'=>60,'maxlength'=>1000)); ?>
		<?php echo $form->error($model,'deskripsi', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'updated_at'); ?>
				<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'updated_by'); ?>
				<?php echo $form->textField($model,'updated_by',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'updated_by', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'created_at'); ?>
				<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class'=>'tiny bigfont button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div>