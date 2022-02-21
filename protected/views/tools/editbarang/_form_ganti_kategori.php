<?= CHtml::label('Pilih Kategori', 'kat-dropdown') ?>
<?= CHtml::dropDownList('kat-dropdown', null, CHtml::listData(KategoriBarang::model()->findAll(array('order' => 'nama')), 'id', 'nama')); ?>
<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-kat']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $(document).on('opened.fndtn.reveal', '#ganti-kat-m[data-reveal]', function() {
        var modal = $(this);
        $("#kat-dropdown").focus();
    });
    $("#tombol-submit-kat").click(function () {
        var kat = $("#kat-dropdown").val();
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxkat': true,
            'kat-id': kat,
            'items': data
        };
        var dataUrl = '<?= $this->createUrl('setkat'); ?>';
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' item berganti kategori menjadi: ' + data.namakat,
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
                $('#ganti-kat-m').foundation('reveal', 'close');
            }
        });
    });
</script>
