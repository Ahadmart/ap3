<?php
/* @var $this MembershipController */
/* @var $model MembershipRegistrationForm */
/* @var $form CActiveForm */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/foundation-datepicker.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/foundation-datepicker.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/locales/foundation-datepicker.id.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="small-12 column">
        <div data-alert class="alert-box radius" style="display:none">
            <span></span>
            <a href="#" class="close button">&times;</a>
        </div>
    </div>
</div>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', [
        'id'                   => 'registrasi-member-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
    ]);
    ?>
    <?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']); ?>

    <div class="row">
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'noTelp'); ?>
            <div class="row collapse">
                <div class="small-3 columns">
                    <span class="prefix"><b>+62</b></span>
                </div>
                <div class="small-9 columns">
                    <?php echo $form->textField($model, 'noTelp', ['size' => 45, 'maxlength' => 45, 'autofocus' => 'autofocus']); ?>
                </div>
            </div>
            <?php echo $form->error($model, 'noTelp', ['class' => 'error']); ?>
        </div>
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'namaLengkap'); ?>
            <?php echo $form->textField($model, 'namaLengkap', ['size' => 45, 'maxlength' => 45]); ?>
            <?php echo $form->error($model, 'namaLengkap', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'jenisKelamin'); ?>
            <?php echo $form->dropDownList($model, 'jenisKelamin', $model->listJenisKelamin()); ?>
            <?php echo $form->error($model, 'jenisKelamin', ['class' => 'error']); ?>
        </div>
        <?php
        /*
<div class="small-12 large-6 columns">
<?php echo $form->labelEx($model, 'tanggalLahir'); ?>
<?php echo $form->textField($model, 'tanggalLahir', ['class' => 'tanggalan']); ?>
<?php echo $form->error($model, 'tanggalLahir', ['class' => 'error']); ?>
</div>
 */
        ?>
        <div class="small-12 large-6 columns">
            <?php echo $form->labelEx($model, 'umur'); ?>
            <?php echo $form->numberField($model, 'umur', ['min' => 1, 'max' => 150]); ?>
            <?php echo $form->error($model, 'umur', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'pekerjaanId'); ?>
            <?php echo $form->dropDownList($model, 'pekerjaanId', CHtml::listData($model->listPekerjaan(), 'id', 'nama')); ?>
            <?php echo $form->error($model, 'pekerjaanId', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'alamat'); ?>
            <?php echo $form->textField($model, 'alamat', ['maxlength' => 500]); ?>
            <?php echo $form->error($model, 'alamat', ['class' => 'error']); ?>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <?php echo $form->labelEx($model, 'keterangan'); ?>
            <?php echo $form->textField($model, 'keterangan', ['maxlength' => 500]); ?>
            <?php echo $form->error($model, 'keterangan', ['class' => 'error']); ?>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont success button right']); ?>
        </div>
    </div>

    <?php $this->endWidget(); ?>
</div>
<script>
    $(function() {
        $('.tanggalan').fdatepicker({
            format: 'dd-mm-yyyy',
            language: 'id'
        });
    });
    $(document).ready(function() {
        $("#registrasi-member-form").submit(function(event) {
            $(".alert-box").slideUp();
            var formData = {
                noTelp: $("#MembershipRegistrationForm_noTelp").val(),
                namaLengkap: $("#MembershipRegistrationForm_namaLengkap").val(),
                jenisKelamin: $("#MembershipRegistrationForm_jenisKelamin").val(),
                // tanggalLahir: $("#MembershipRegistrationForm_tanggalLahir").val(),
                umur: $("#MembershipRegistrationForm_umur").val(),
                pekerjaanId: $("#MembershipRegistrationForm_pekerjaanId").val(),
                alamat: $("#MembershipRegistrationForm_alamat").val(),
                keterangan: $("#MembershipRegistrationForm_keterangan").val(),
            };
            $.ajax({
                type: "POST",
                url: "<?= $this->createUrl('prosesregistrasi') ?>",
                data: formData,
            }).done(function(r) {
                // console.log(data);
                r = JSON.parse(r)
                $(".alert-box").slideDown(500, function() {
                    if (r.statusCode == 200) {
                        $(".alert-box").removeClass("alert");
                        $(".alert-box").addClass("warning");
                        $(".alert-box>span").html("Sukses: " + r.data.msg + ". Nomor: <strong>" + r.data.nomor + "</strong>")
                        setTimeout(openView(r.data.nomor), 3000)
                    } else {
                        $(".alert-box").removeClass("warning");
                        $(".alert-box").addClass("alert");
                        $(".alert-box>span").html(r.statusCode + ":" + r.error.type + ". " + r.error.description)
                    }
                })

            });

            event.preventDefault();
        });
    });

    function openView(nomor) {
        window.location.href = "<?= $this->createUrl('/membership') ?>/" + nomor;
    }
</script>