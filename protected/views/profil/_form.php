<?php
/* @var $this ProfilController */
/* @var $model Profil */
/* @var $form CActiveForm */
?>

<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id' => 'profil-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>

    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'tipe_id'); ?>
            <?php echo $form->dropDownList($model, 'tipe_id', CHtml::listData(TipeProfil::model()->findAll(), 'id', 'nama')); ?>
            <?php echo $form->error($model, 'tipe_id', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'nama'); ?>
            <?php echo $form->textField($model, 'nama', ['size' => 60, 'maxlength' => 100]); ?>
            <?php echo $form->error($model, 'nama', ['class' => 'error']); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="small-12 medium-4 columns">
            <?php echo $form->labelEx($model, 'nomor'); ?>
            <?php echo $form->textField($model, 'nomor', ['size' => 45, 'maxlength' => 45]); ?>
            <?php echo $form->error($model, 'nomor', ['class' => 'error']); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'identitas'); ?>
            <?php echo $form->textField($model, 'identitas', ['size' => 60, 'maxlength' => 255]); ?>
            <?php echo $form->error($model, 'identitas', ['class' => 'error']); ?>
        </div>
        <div class="small-12 medium-4  columns">
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
    </div>
    <hr>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat1'); ?>
            <?php echo $form->textField($model, 'alamat1', ['size' => 60, 'maxlength' => 100]); ?>
            <?php echo $form->error($model, 'alamat1', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat2'); ?>
            <?php echo $form->textField($model, 'alamat2', ['size' => 60, 'maxlength' => 100]); ?>
            <?php echo $form->error($model, 'alamat2', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat3'); ?>
            <?php echo $form->textField($model, 'alamat3', ['size' => 60, 'maxlength' => 100]); ?>
            <?php echo $form->error($model, 'alamat3', ['class' => 'error']); ?>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="small-12 medium-4 columns">
            <?php echo $form->labelEx($model, 'telp'); ?>
            <?php echo $form->textField($model, 'telp', ['size' => 20, 'maxlength' => 20]); ?>
            <?php echo $form->error($model, 'telp', ['class' => 'error']); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'hp'); ?>
            <?php echo $form->textField($model, 'hp', ['size' => 20, 'maxlength' => 20]); ?>
            <?php echo $form->error($model, 'hp', ['class' => 'error']); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'surel'); ?>
            <?php echo $form->textField($model, 'surel', ['size' => 40, 'maxlength' => 255]); ?>
            <?php echo $form->error($model, 'surel', ['class' => 'error']); ?>
        </div>
        <div class="small-12 medium-4  columns">
            <?php echo $form->labelEx($model, 'jenis_kelamin'); ?>
            <?php echo $form->dropDownList($model, 'jenis_kelamin', $model->listJenisKelamin(), ['prompt' => 'Pilih satu..']); ?>
            <?php echo $form->error($model, 'jenis_kelamin', ['class' => 'error']); ?>
        </div>

        <div class="small-12 medium-4 columns end">
            <?php echo $form->labelEx($model, 'tanggal_lahir'); ?>
            <?php echo $form->textField($model, 'tanggal_lahir', ['class' => 'tanggalan', 'value' => $model->isNewRecord ? '' : $model->tanggal_lahir]); ?>
            <?php echo $form->error($model, 'tanggal_lahir', ['class' => 'error']); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', ['size' => 60, 'maxlength' => 1000]); ?>
            <?php echo $form->error($model, 'keterangan', ['class' => 'error']); ?>
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
    });

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