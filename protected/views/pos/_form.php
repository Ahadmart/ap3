<?php
/* @var $this PosController */
/* @var $model Penjualan */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'penjualan-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,'Error: Perbaiki input',null,array('class'=>'panel callout')); ?>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'nomor'); ?>
				<?php echo $form->textField($model,'nomor',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'nomor', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'tanggal'); ?>
				<?php echo $form->textField($model,'tanggal'); ?>
		<?php echo $form->error($model,'tanggal', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'profil_id'); ?>
				<?php echo $form->textField($model,'profil_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'profil_id', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'hutang_piutang_id'); ?>
				<?php echo $form->textField($model,'hutang_piutang_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'hutang_piutang_id', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'status'); ?>
				<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status', array('class'=>'error')); ?>
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