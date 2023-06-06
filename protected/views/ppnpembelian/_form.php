<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pembelian-ppn-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model,'Error: Perbaiki input',null,array('class'=>'panel callout')); ?>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'pembelian_id'); ?>
				<?php echo $form->textField($model,'pembelian_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'pembelian_id', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'no_faktur_pajak'); ?>
				<?php echo $form->textField($model,'no_faktur_pajak',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'no_faktur_pajak', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'total_ppn_hitung'); ?>
				<?php echo $form->textField($model,'total_ppn_hitung',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'total_ppn_hitung', array('class'=>'error')); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
		<?php echo $form->labelEx($model,'total_ppn_faktur'); ?>
				<?php echo $form->textField($model,'total_ppn_faktur',array('size'=>18,'maxlength'=>18)); ?>
		<?php echo $form->error($model,'total_ppn_faktur', array('class'=>'error')); ?>
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