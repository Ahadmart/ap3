<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'pembelian-ppn-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	]); ?>

	<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

	<?php echo $form->hiddenField($model, 'pembelian_id'); ?>
	<div class="small-12 columns">
		<div class="row collapse">
			<label>Pembelian</label>
			<div class="small-8 columns">
				<?php echo CHtml::textField('nomorpembelian', empty($model->pembelian_id) ? '' : $model->pembelian->nomor, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
			</div>
			<div class="small-1 columns">
				<a class="tiny bigfont secondary button postfix" id="tombol-hapuspembelian"><i class="fa fa-eraser"></i></a>
			</div>
			<div class="small-3 columns">
				<a class="tiny bigfont button postfix" id="tombol-browse" accesskey="p"><span class="ak">P</span>ilih..</a>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'total_ppn_hitung'); ?>
			<?php echo $form->textField($model, 'total_ppn_hitung', ['size' => 18, 'maxlength' => 18, 'disabled' => 'disabled']); ?>
			<?php echo $form->error($model, 'total_ppn_hitung', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'no_faktur_pajak'); ?>
			<?php echo $form->textField($model, 'no_faktur_pajak', ['size' => 45, 'maxlength' => 45]); ?>
			<?php echo $form->error($model, 'no_faktur_pajak', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'total_ppn_faktur'); ?>
			<?php echo $form->textField($model, 'total_ppn_faktur', ['size' => 18, 'maxlength' => 18]); ?>
			<?php echo $form->error($model, 'total_ppn_faktur', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'status'); ?>
			<?php echo $form->textField($model, 'status'); ?>
			<?php echo $form->error($model, 'status', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>