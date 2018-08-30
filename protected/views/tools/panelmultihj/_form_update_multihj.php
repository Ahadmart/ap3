<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'harga-jual-multi-form',
    'enableAjaxValidation' => false,
        ]);
?>
<div class="row">
    <div class="small-12 columns">
        <h5>Update Multi Harga Jual</h5>
        <label><?= $barang->nama; ?> (<?= $barang->barcode; ?>)</label>
        <div class="row collapse">
            <div class="medium-3 large-4 columns">
                <?= $form->dropDownList($hjMultiModel, 'satuan_id', CHtml::listData(SatuanBarang::model()->findAll(['order' => 'nama']), 'id', 'nama'), ['prompt' => 'Pilih satuan..']); ?>
            </div>
            <div class="medium-3 large-2 columns">
                <?= $form->textField($hjMultiModel, 'qty', ['placeholder' => 'Isi', 'value' => '', 'class' => 'i-multihj']); ?>
            </div>
            <div class="medium-3 columns">
                <?= $form->textField($hjMultiModel, 'harga', ['placeholder' => 'Harga Satuan', 'class' => 'i-multihj']); ?>
            </div>
            <div class="medium-3 columns">
                <?php
                // echo CHtml::ajaxSubmitButton('Update', Yii::app()->createUrl('/barang/updatehargajualmulti', ['id' => $barang->id]), [
                //     'success' => "function () {
                //                     $('#barang-grid').yiiGridView('update');
                //             }"
                //         ], [
                //     'class' => 'button postfix',
                //     'id'    => 'tombol-update-hj-multi'
                //     ]);
                ?>
                <?= CHtml::link('Update', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-update-hj-multi']); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']); ?>


<script>
    $(document).on("keypress", "#harga-jual-multi-form", function (event) {
        return event.keyCode != 13;
    });

    $(".i-multihj").keyup(function (e) {
        if (e.keyCode === 13) {
            $("#tombol-update-hj-multi").click();
        }
    });

    $("#tombol-update-hj-multi").click(function () {
        var dataKirim = $("#harga-jual-multi-form").serialize();
        var dataUrl = '<?= Yii::app()->createUrl('barang/updatehargajualmulti', ['id' => $barang->id]) ?>';
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                $('#barang-grid').yiiGridView('update');
                $('#update-multihj-m').foundation('reveal', 'close');
            }
        });
    });

</script>
