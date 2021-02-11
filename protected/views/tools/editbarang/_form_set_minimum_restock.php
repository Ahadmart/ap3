<?= CHtml::label('Set Minimum Restock', 'input-min-restock') ?>
<?= CHtml::textField('input-min-restock'); ?>
<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-set-minimumrestock']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $("#tombol-submit-set-minimumrestock").click(function() {
        var value = $("#input-min-restock").val();
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxrak': true,
            'minrestock-value': value,
            'items': data
        };
        var dataUrl = '<?= $this->createUrl('setminimumrestock'); ?>';
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' item diubah minimum restocknya menjadi: ' + data.minrestock_value,
                        time: 3000
                    });
                    $('#barang-grid').yiiGridView('update');
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 5000
                    });
                }
                $('#ganti-rak-m').foundation('reveal', 'close');
            }
        });
    });
</script>