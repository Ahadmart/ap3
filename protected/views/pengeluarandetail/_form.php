<?php
/* @var $this PengeluarandetailController */
/* @var $model PengeluaranDetail */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pengeluaran-detail-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,'Error: Perbaiki input',null,array('class'=>'panel callout')); ?>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'pengeluaran_id'); ?>
				<?php echo $form->textField($model,'pengeluaran_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'pengeluaran_id', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'akun_id'); ?>
				<?php echo $form->textField($model,'akun_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'akun_id', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'nomor_dokumen'); ?>
				<?php echo $form->textField($model,'nomor_dokumen',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'nomor_dokumen', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'keterangan'); ?>
				<?php echo $form->textField($model,'keterangan',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'keterangan', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'jumlah'); ?>
				<?php echo $form->textField($model,'jumlah',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'jumlah', array('class'=>'error')); ?>
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