<?php
/* @var $this ReportController */
/* @var $model ReportPlsForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'report-pls-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
]);
?>
<?php
echo $form->errorSummary($model, 'Error: Perbaiki input', null, ['class' => 'panel callout']);
echo $form->hiddenField($model, 'profilId');
?>

<div class="row">
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo $form->labelEx($model, 'jumlahHari');
        echo $form->numberField($model, 'jumlahHari', ['value' => empty($model->jumlahHari) ? '30' : $model->jumlahHari]);
        echo $form->error($model, 'jumlahHari', ['class' => 'error']);
        ?>
    </div>
    <div class="small-12 medium-4 large-2 columns">
        <?php
        echo $form->labelEx($model, 'orderPeriod');
        echo $form->numberField($model, 'orderPeriod', ['value' => empty($model->orderPeriod) ? '7' : $model->orderPeriod]);
        echo $form->error($model, 'orderPeriod', ['class' => 'error']);
        ?>
    </div>
    <div class="medium-6 large-3 columns">
        <div class="row collapse">
            <label>Profil (Opsional)</label>
            <div class="small-9 columns">
                <?php echo CHtml::textField('profil', empty($model->profilId) ? '' : $model->namaProfil, ['size' => 60, 'maxlength' => 500, 'disabled' => 'disabled']); ?>
            </div>
            <div class="small-3 columns">
                <a class="tiny bigfont button postfix" id="tombol-browse-profil" accesskey="p">
                    <span class="ak">P</span>ilih..</a>
            </div>
        </div>
    </div>
    <div class="medium-6 large-3 end columns">
        <?php
        echo $form->labelEx($model, 'sortBy');

        echo $form->dropDownList($model, 'sortBy', $model->listSortBy(), [
            'options' => [
                isset($model->sortBy) ? $model->sortBy : ReportPlsForm::SORT_BY_SISA_HARI_ASC => ['selected' => 'selected'],
            ],
        ]);

        echo $form->error($model, 'sortBy', ['class' => 'error']);
        ?>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Submit', ['class' => 'tiny bigfont button right']); ?>
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
</script>