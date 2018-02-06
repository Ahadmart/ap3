<?php
    /* @var $this PoController */
    /* @var $model Po */
    /* @var $form CActiveForm */
?>

	<div class="form">

		<?php $form = $this->beginWidget('CActiveForm', [
                'id'                   => 'po-form',
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // There is a call to performAjaxValidation() commented in generated controller code.
                // See class documentation of CActiveForm for details on this.
                'enableAjaxValidation' => false,
            ]);

        echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']);?>

		<div class="row">
			<div class="small-12 columns">
				<?php
                    echo $form->labelEx($model, 'profil_id');

                    echo $form->dropDownList($model, 'profil_id', CHtml::listData($supplierList, 'id', 'nama'), [
                        'empty'     => 'Pilih satu..',
                        'autofocus' => 'autofocus',
                    ]);

                    echo $form->error($model, 'profil_id', ['class' => 'error']);
                ?>
			</div>
		</div>
		<div class="row">
			<div class="small-12 columns">
				<input id="checkbox_profil" type="checkbox" name="semua_profil">
				<label for="checkbox_profil">Tampilkan semua profil</label>
			</div>
		</div>
		<script>
			$("#checkbox_profil").change(function () {
				if (this.checked) {
					console.log('semua');
					$("#Po_profil_id").load("<?php echo $this->createUrl('ambilprofil', ['tipe' => $this::PROFIL_ALL]); ?>");
				} else {
					console.log('supplier');
					$("#Po_profil_id").load("<?php echo $this->createUrl('ambilprofil', ['tipe' => $this::PROFIL_SUPPLIER]); ?>");
				}
			});
		</script>
		<div class="row">
			<div class="large-6 columns">
				<?php
                    echo $form->labelEx($model, 'referensi');
                    echo $form->textField($model, 'referensi', ['size' => 45, 'maxlength' => 45]);
                    echo $form->error($model, 'referensi', ['class' => 'error']);
                ?>
			</div>

			<div class="large-6 columns">
				<?php
                    echo $form->labelEx($model, 'tanggal_referensi');
                    echo $form->textField($model, 'tanggal_referensi', ['class' => 'tanggalan', 'value' => $model->isNewRecord ? date('d-m-Y') : date_format(date_create_from_format('Y-m-d', $model->tanggal_referensi), 'd-m-Y')]);
                    echo $form->error($model, 'tanggal_referensi', ['class' => 'error']);
                ?>
			</div>
		</div>

		<div class="row">
			<div class="small-12 columns">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan', ['class' => 'tiny bigfont button']); ?>
			</div>
		</div>

		<?php $this->endWidget();?>

	</div>

	<?php
        Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
        Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
    ?>

		<script>
			$(function () {
				$('.tanggalan').fdatepicker({
					format: 'dd-mm-yyyy',
					language: 'id'
				});
			});
		</script>