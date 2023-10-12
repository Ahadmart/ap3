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
	<div class="row">
		<div class="small-12 columns">
			<h4><small>Pembelian</small> <?= Yii::app()->format->nomorDokumen($model->pembelian->nomor) ?> <small>dari</small> <?= $model->pembelian->profil->nama ?></h4>
		</div>
		<div class="medium-8 columns">
			<?php echo $form->labelEx($model, 'npwp'); ?>
			<?php echo $form->textField($model, 'npwp', [
				'size'        => 45,
				'maxlength'   => 45,
				'placeholder' => '___.___.___._-___.___',
				'data-slots'  => '_',
				'data-accept' => '\d'
			]); ?>
			<?php echo $form->error($model, 'npwp', ['class' => 'error']); ?>
		</div>
		<div class="small-12 columns">
			<h4><small>Total Ppn hitung</small> <?= Yii::app()->format->uang($model->total_ppn_hitung) ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="medium-8 columns">
			<?php echo $form->labelEx($model, 'no_faktur_pajak'); ?>
			<?php echo $form->textField($model, 'no_faktur_pajak', [
				'size'        => 45,
				'maxlength'   => 45,
				'placeholder' => '___.___-__.________',
				'data-slots'  => '_',
				'data-accept' => '\d'
			]); ?>
			<?php echo $form->error($model, 'no_faktur_pajak', ['class' => 'error']); ?>
		</div>
		<div class="medium-4 columns">
			<?php echo $form->labelEx($model, 'total_ppn_faktur'); ?>
			<?php echo $form->textField($model, 'total_ppn_faktur', ['size' => 18, 'maxlength' => 18]); ?>
			<?php echo $form->error($model, 'total_ppn_faktur', ['class' => 'error']); ?>
		</div>
	</div>

	<?php
	/*
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'status'); ?>
            <?php echo $form->textField($model, 'status'); ?>
            <?php echo $form->error($model, 'status', ['class' => 'error']); ?>
        </div>
    </div>
    */
	?>
	<div class="row">
		<div class="small-12 columns">
			<?php echo CHtml::submitButton('Validasi', ['class' => 'tiny bigfont button']); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>
<script>
	// Sumber: https://stackoverflow.com/questions/12578507/implement-an-input-with-a-mask
	document.addEventListener('DOMContentLoaded', () => {
		for (const el of document.querySelectorAll("[placeholder][data-slots]")) {
			const pattern = el.getAttribute("placeholder"),
				slots = new Set(el.dataset.slots || "_"),
				prev = (j => Array.from(pattern, (c, i) => slots.has(c) ? j = i + 1 : j))(0),
				first = [...pattern].findIndex(c => slots.has(c)),
				accept = new RegExp(el.dataset.accept || "\\d", "g"),
				clean = input => {
					input = input.match(accept) || [];
					return Array.from(pattern, c =>
						input[0] === c || slots.has(c) ? input.shift() || c : c
					);
				},
				format = () => {
					const [i, j] = [el.selectionStart, el.selectionEnd].map(i => {
						i = clean(el.value.slice(0, i)).findIndex(c => slots.has(c));
						return i < 0 ? prev[prev.length - 1] : back ? prev[i - 1] || first : i;
					});
					el.value = clean(el.value).join``;
					el.setSelectionRange(i, j);
					back = false;
				};
			let back = false;
			el.addEventListener("keydown", (e) => back = e.key === "Backspace");
			el.addEventListener("input", format);
			el.addEventListener("focus", format);
			el.addEventListener("blur", () => el.value === pattern && (el.value = ""));
		}
	});
</script>