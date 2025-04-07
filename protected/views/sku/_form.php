<?php
/* @var $this SkuController */
/* @var $model Sku */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'sku-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	]); ?>

	<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'nomor'); ?>
			<?php echo $form->textField($model, 'nomor', ['size' => 30, 'maxlength' => 30]); ?>
			<?php echo $form->error($model, 'nomor', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'nama'); ?>
			<?php echo $form->textField($model, 'nama', ['size' => 45, 'maxlength' => 45]); ?>
			<?php echo $form->error($model, 'nama', ['class' => 'error']); ?>
		</div>
	</div>

	<?php
	/*
	<div class="row">
	<div class="small-12 columns">
	<?php echo $form->labelEx($model, 'struktur_id'); ?>
	<?php echo $form->textField($model, 'struktur_id', ['size' => 10, 'maxlength' => 10]); ?>
	<?php echo $form->error($model, 'struktur_id', ['class' => 'error']); ?>
	</div>
	</div>
	*/
	?>

	<?php
	if (!$model->isNewRecord) {
	?>
		<div class="row">
			<div class="small-12 columns">
				<?php echo $form->labelEx($model, 'kategori_id'); ?>
				<?php
				echo $form->dropDownList($model, 'kategori_id', CHtml::listData(KategoriBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), [
					'empty' => 'Pilih satu..',
				]); ?>
				<?php echo $form->error($model, 'kategori_id', ['class' => 'error']); ?>
			</div>
		</div>
	<?php
	}
	?>

	<div class="row">
		<div class="small-12 columns">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>