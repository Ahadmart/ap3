<div class="row collapse">
    <div class="medium-3 large-4 columns">
        <?= CHtml::dropDownList('HargaJualMulti[satuan_id]', '', CHtml::listData(SatuanBarang::model()->findAll(['order'=>'nama']), 'id', 'nama'), ['prompt'=>'Pilih satuan..']) ?>
    </div>
    <div class="medium-3 large-2 columns">
        <?= CHtml::textField('HargaJualMulti[qty]', '', ['placeholder'=>'Isi', 'value'=>'']) ?>
    </div>
    <div class="medium-3 columns">
        <?= CHtml::textField('HargaJualMulti[harga]', '', ['placeholder' => 'Harga Satuan']) ?>
    </div>
    <div class="medium-3 columns">
        <?php
        echo CHtml::ajaxSubmitButton('Update', $this->createUrl('updatehjmulti'), [
            'success' => "function () {
                        $.fn.yiiGridView.update('harga-jual-multi-grid');
                        $('#hj-aktif').load('" . Yii::app()->createUrl('barang/listhargajualmulti', ['id'=>'']) . '\'+$("#barang-id").val());
                    }'
                ], [
            'class' => 'button postfix',
            'id'    => 'tombol-update-hj-multi',
            'style' => 'text-align:center',
            ]);
        ?>
    </div>
</div>
<script>
    function submitHJMultiOnly(){
        $("#tombol-update-hj-multi").click();
    }
    $("#HargaJualMulti_qty").keyup(function(e) {
        if (e.keyCode === 13) {
            submitHJMultiOnly();
        }
    });
    $("#HargaJualMulti_harga").keyup(function(e) {
        if (e.keyCode === 13) {
            submitHJMultiOnly();
        }
    });
</script>