<?php
/* @var $this SkutransferController */
/* @var $model SkuTransfer */
/* @var $form CActiveForm */
?>

<div class="form">

	<?php $form = $this->beginWidget('CActiveForm', [
		'id'                   => 'sku-transfer-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation' => false,
	]); ?>

	<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

	<?php echo $form->hiddenField($model, 'sku_id'); ?>

	<div class="row">
		<div class="small-12 columns">
			<label for="scan" class="required">Dari Barang <span class="required">*</span></label>
			<div class="row collapse">
				<div class="small-3 large-2 columns">
					<span class="prefix" id="scan-icon"><i class="fa fa-barcode fa-2x"></i></span>
				</div>
				<div class="small-6 large-8 columns">
					<?php
					$barcode = !is_null($model->sku) ? $model->sku->barang->barcode : ''
					?>
					<input id="scan" type="text" placeholder="Scan [B]arcode / Input nama" accesskey="b" <?php echo $model->isNewRecord ? '' : 'value="' . $barcode . '" autofocus="autofocus"' ?> />
				</div>
				<div class="small-3 large-2 columns">
					<a href="#" class="button postfix" id="tombol-scan-ok"><i class="fa fa-level-down fa-2x fa-rotate-90"></i></a>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<div class="panel" id="info-sku" style="display: none; padding-bottom: 15px; margin-left: none; margin-right: none">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="small-12 columns">
			<?php echo $form->labelEx($model, 'keterangan'); ?>
			<?php echo $form->textField($model, 'keterangan', ['size' => 60, 'maxlength' => 500]); ?>
			<?php echo $form->error($model, 'keterangan', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="large-6 columns">
			<?php echo $form->labelEx($model, 'referensi'); ?>
			<?php echo $form->textField($model, 'referensi', ['size' => 45, 'maxlength' => 45]); ?>
			<?php echo $form->error($model, 'referensi', ['class' => 'error']); ?>
		</div>

		<div class="large-6 columns">
			<?php echo $form->labelEx($model, 'tanggal_referensi'); ?>
			<?php echo $form->textField($model, 'tanggal_referensi', ['class' => 'tanggalan']); ?>
			<?php echo $form->error($model, 'tanggal_referensi', ['class' => 'error']); ?>
		</div>
	</div>

	<div class="row">
		<div class="small-12 columns">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>

</div>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>

<script>
	$(function() {
		$('.tanggalan').fdatepicker({
			format: 'dd-mm-yyyy',
			language: 'id'
		});
		$(document).on('click', "#tombol-scan-ok", function() {
			dataUrl = '<?php echo $this->createUrl('getdatasku'); ?>';
			dataKirim = {
				barcode: $("#scan").val()
			};
			console.log(dataUrl);
			/* Jika tidak ada sku, keluar! */
			if ($("#scan").val() === '') {
				return false;
			}

			$.ajax({
				type: 'POST',
				url: dataUrl,
				data: dataKirim,
				success: function(data) {
					if (data.sukses) {
						var hasil = '<h5><small>' + data.nomor + ' </small>' + data.nama + "</h5>";
						$("#info-sku").html(hasil);
						$("#info-sku").show();
						$("#SkuTransfer_sku_id").val(data.skuId);
						$("#SkuTransfer_keterangan").focus();
					} else {
						$.gritter.add({
							title: 'Error ' + data.error.code,
							text: data.error.msg,
							time: 3000,
							//class_name: 'gritter-center'
						});
					}
				}
			});
		});
	});

	$("#scan").autocomplete({
		source: "<?php echo $this->createUrl('caribarang'); ?>",
		minLength: 3,
		search: function(event, ui) {
			$("#scan-icon").html('<img src="<?php echo Yii::app()->theme->baseUrl; ?>/css/3.gif" />');
		},
		response: function(event, ui) {
			$("#scan-icon").html('<i class="fa fa-barcode fa-2x"></i>');
		},
		select: function(event, ui) {
			console.log(ui.item ?
				"Nama: " + ui.item.label + "; Sku " + ui.item.value :
				"Nothing selected, input was " + this.value);
		}
	}).autocomplete("instance")._renderItem = function(ul, item) {
		return $("<li>")
			.append("<a>" + item.label + "<br /><small>" + item.value + '</small></a>')
			.appendTo(ul);
	};

	$("#scan").on("keydown", function(e) {
		if (e.keyCode === 13) {
			e.preventDefault();
			$("#tombol-scan-ok").click();
		}
	});
</script>