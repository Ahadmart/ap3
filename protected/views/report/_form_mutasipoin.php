<?php
/* @var $this ReportController */
/* @var $model ReportMutasiPoinForm */
/* @var $form CActiveForm */
?>
<div class="row">
    <div class="small-12 column">
        <div data-alert class="alert-box radius" style="display:none">
            <span></span>
            <a href="#" class="close button">&times;</a>
        </div>
    </div>
</div>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-mutasipoin-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<div class="row">
    <div class="small-12 medium-4 large-3 columns">
        <?php echo $form->labelEx($model, 'nomor'); ?>
        <?php echo $form->textField($model, 'nomor'); ?>
        <?php echo $form->error($model, 'nomor', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan',)); ?>
        <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns end">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan', 'value' => empty($model->sampai) ? date('d-m-Y') : $model->sampai)); ?>
        <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
    </div>

</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', array('class' => 'tiny bigfont button right')); ?>
    </div>
</div>

<?php
$this->endWidget();

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

    $(document).ready(function() {
        $("#report-mutasipoin-form").submit(function(event) {
            $(".alert-box").slideUp();
            $("#tabel-mutasipoin").addClass("grid-loading");
            var formData = {
                nomor: $("#ReportMutasiPoinForm_nomor").val(),
                dari: $("#ReportMutasiPoinForm_dari").val(),
                sampai: $("#ReportMutasiPoinForm_sampai").val()
            };
            $.ajax({
                type: "POST",
                url: "<?= $this->createUrl('/membership/reportmutasipoin') ?>",
                data: formData,
            }).done(function(r) {
                // console.log(data);
                r = JSON.parse(r)
                $(".alert-box").slideUp(500, function() {
                    if (r.statusCode == 200) {
                        $(".alert-box").removeClass("alert");
                        $(".alert-box").addClass("warning");
                        $(".alert-box>span").html("Ditemukan " + r.data.mutasi.length + " transaksi")
                        isiTabel(r.data)
                    } else {
                        $(".alert-box").removeClass("warning");
                        $(".alert-box").addClass("alert");
                        $(".alert-box>span").html(r.statusCode + ":" + r.error.type +
                            ". " + r.error.description)
                    }
                    $(".alert-box").slideDown(500)
                    $("#tabel-mutasipoin").removeClass("grid-loading")
                })

            });

            event.preventDefault();
        });
    });
</script>