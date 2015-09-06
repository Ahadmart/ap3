<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php
	$form = $this->beginWidget('CActiveForm', array(
		 'id' => 'user-form',
		 // Please note: When you enable ajax validation, make sure the corresponding
		 // controller action is handling ajax validation correctly.
		 // There is a call to performAjaxValidation() commented in generated controller code.
		 // See class documentation of CActiveForm for details on this.
		 'enableAjaxValidation' => false,
	));
	?>

	<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'nama'); ?>
			<?php echo $form->textField($model, 'nama', array('size' => 45, 'maxlength' => 45, 'autofocus' => 'autofocus')); ?>
			<?php echo $form->error($model, 'nama', array('class' => 'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'nama_lengkap'); ?>
			<?php echo $form->textField($model, 'nama_lengkap', array('size' => 60, 'maxlength' => 100)); ?>
			<?php echo $form->error($model, 'nama_lengkap', array('class' => 'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'newPassword'); ?>
			<?php echo $form->passwordField($model, 'newPassword', array('size' => 60, 'maxlength' => 512)); ?>
			<?php echo $form->error($model, 'newPassword', array('class' => 'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'newPasswordRepeat'); ?>
			<?php echo $form->passwordField($model, 'newPasswordRepeat', array('size' => 60, 'maxlength' => 512)); ?>
			<?php echo $form->error($model, 'newPasswordRepeat', array('class' => 'error')); ?>
		</div>
	</div>
	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'theme_id'); ?>
			<?php echo $form->dropDownList($model, 'theme_id', Theme::model()->listTheme()); ?>
			<?php echo $form->error($model, 'theme_id', array('class' => 'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', array('class' => 'tiny bigfont button')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>