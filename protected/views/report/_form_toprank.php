<?php
/* @var $this ReportController */
/* @var $model ReportTopRankForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'report-toprank-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>

<?php echo $form->hiddenField($model, 'profilId'); ?>
<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'dari'); ?>
        <?php echo $form->textField($model, 'dari', array('class' => 'tanggalan', 'value' => empty($model->dari) ? date('d-m-Y') : $model->dari)); ?>
        <?php echo $form->error($model, 'dari', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'sampai'); ?>
        <?php echo $form->textField($model, 'sampai', array('class' => 'tanggalan', 'value' => empty($model->sampai) ? date('d-m-Y') : $model->sampai)); ?>
        <?php echo $form->error($model, 'sampai', array('class' => 'error')); ?>
    </div>
    <div class="medium-6 large-5 columns">
        <div class="row collapse">
            <label>Profil (Supplier)</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, array('size' => 60, 'maxlength' => 500, 'disabled' => 'disabled')); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-profil" accesskey="p"><span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="small-12 medium-6 large-3 columns">
        <?php echo $form->labelEx($model, 'kategoriId'); ?>
        <?php echo $form->dropDownList($model, 'kategoriId', $model->filterKategori()); ?>
        <?php echo $form->error($model, 'kategoriId', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'rakId'); ?>
        <?php echo $form->dropDownList($model, 'rakId', $model->filterRak()); ?>
        <?php echo $form->error($model, 'rakId', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'limit'); ?>
        <?php echo $form->textField($model, 'limit'); ?>
        <?php echo $form->error($model, 'limit', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'sortBy'); ?>
        <?php echo $form->dropDownList($model, 'sortBy', $model->listSortBy()); ?>
        <?php echo $form->error($model, 'sortBy', array('class' => 'error')); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'strukLv1'); ?>
        <?php echo $form->dropDownList($model, 'strukLv1', StrukturBarang::listStrukLv1()); ?>
        <?php echo $form->error($model, 'strukLv1', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'strukLv2'); ?>
        <?php
        $strukLv2List = ['' => '[SEMUA]'];
        if (!empty($model->strukLv1)) {
            $strukLv2List = StrukturBarang::listStrukLv2($model->strukLv1);
            // var_dump($strukLv2List);
        }
        ?>
        <?php echo $form->dropDownList($model, 'strukLv2', $strukLv2List); ?>
        <?php echo $form->error($model, 'strukLv2', ['class' => 'error']); ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php echo $form->labelEx($model, 'strukLv3'); ?>
        <?php
        $strukLv3List = ['' => '[SEMUA]'];
        if (!empty($model->strukLv2)) {
            $strukLv3List = StrukturBarang::listStrukLv3($model->strukLv2);
        }
        ?>
        <?php echo $form->dropDownList($model, 'strukLv3', $strukLv3List); ?>
        <?php echo $form->error($model, 'strukLv3', ['class' => 'error']); ?>
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
    $("#ReportTopRankForm_strukLv1").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportTopRankForm_strukLv2']").text('Loading..');
        $("#ReportTopRankForm_strukLv2").load("<?= $this->createUrl('ambilstrukturlv2', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportTopRankForm_strukLv2']").text('Struktur Level 2');
        })
        $("#ReportTopRankForm_strukLv3").html("<option value=''>[SEMUA]</option>")
    })

    $("#ReportTopRankForm_strukLv2").change(function() {
        var parentId = $(this).val()
        $("label[for='ReportTopRankForm_strukLv3']").text('Loading..');
        $("#ReportTopRankForm_strukLv3").load("<?= $this->createUrl('ambilstrukturlv3', ['parent-id' => '']); ?>" + parentId, function() {
            $("label[for='ReportTopRankForm_strukLv3']").text('Struktur Level 3');
        })
    })
</script>