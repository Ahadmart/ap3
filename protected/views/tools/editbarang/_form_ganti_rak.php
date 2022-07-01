<?= CHtml::label('Pilih rak', 'rak-dropdown') ?>
<?= CHtml::dropDownList('rak-dropdown', null, CHtml::listData(RakBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama')); ?>
<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-rak']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $(document).on('opened.fndtn.reveal', '#ganti-rak-m[data-reveal]', function() {
        var modal = $(this);
        // console.log("Ganti rak opened");
        $("#rak-dropdown").focus();
    });
    $("#tombol-submit-rak").click(function() {
        var rak = $("#rak-dropdown").val();
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxrak': true,
            'rak-id': rak,
            'items': data
        };
        var dataUrl = '<?= $this->createUrl('setrak'); ?>';
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' item pindah ke rak: ' + data.namarak,
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