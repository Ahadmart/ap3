<?= CHtml::label('Set Minimum Restock', 'input-min-restock') ?>
<?= CHtml::textField('input-min-restock', '0', ['autofocus' => 'autofocus']) ?>
<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-set-minimumrestock']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $(document).on('opened.fndtn.reveal', '#ganti-minrestock-m[data-reveal]', function() {
        var modal = $(this);
        $("#input-min-restock").focus();
        $("#input-min-restock").select();
    });
    $("#input-min-restock").on('keyup', function(e) {
        var k = e.key;
        if (k === "Enter") e.preventDefault();
        if (k === "Enter") {
            $("#tombol-submit-set-minimumrestock").click();
        }
    })
    $("#tombol-submit-set-minimumrestock").click(function() {
        var value = $("#input-min-restock").val();
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxminrestock': true,
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
                $('#ganti-minrestock-m').foundation('reveal', 'close');
            }
        });
    });
</script>