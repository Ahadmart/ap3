<?php
/* @var $this CetaklabelrakController */
/* @var $model CetakLabelRakLayoutForm */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'cetak-label-rak-layout-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // See class documentation of CActiveForm for details on this,
    // you need to use the performAjaxValidation()-method described there.
    'enableAjaxValidation' => false,
    'htmlOptions' => array('target' => '_blank'),
    'method' => 'GET'
        ));
?>
<?php echo $form->errorSummary($model, 'Error: Perbaiki input', null, array('class' => 'panel callout')); ?>
<div class="row">
    <div class="small-12 large-6 columns">
        <?php echo $form->label($model, 'layoutId'); ?>
        <?php
        echo $form->dropDownList($model, 'layoutId', CetakLabelRakLayoutForm::listLayout());
        ?>
    </div>
    <div class="small-12 large-6 columns">
        <?php echo $form->label($model, 'kertasId'); ?>
        <?php
        echo $form->dropDownList($model, 'kertasId', CetakLabelRakLayoutForm::listKertas());
        ?>
    </div>
</div>

<div class="row">
    <div class="small-12 columns">
        <?php echo CHtml::submitButton('Cetak', array('id' => 'tombol-cetak', 'class' => 'tiny bigfont button right')); ?>
    </div>
</div>

<?php
$this->endWidget();
?>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-ui-ac.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/jquery-ui.min-ac.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $("#cetak-label-rak-form").submit(function (event) {
            event.preventDefault();
            dataUrl = '<?php echo $this->createUrl('tambahkanbarang'); ?>';
            dataKirim = $(this).serializeArray();
            console.log(dataKirim);
            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.gritter.add({
                            title: 'Sukses',
                            text: data.rowAffected + ' barang ditambahkan',
                            time: 3000
                        });
                        $.fn.yiiGridView.update('label-rak-cetak-grid');
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 3000
                        });
                    }

                }
            });
        });
    });
</script>