<?= CHtml::label('Pilih Printer', 'printer-dropdown') ?>
<?= CHtml::dropDownList('printer-dropdown', null, CHtml::listData($printerLabel, 'id', 'nama')); ?>
<?= CHtml::label('Pilih Layout', 'layout-dropdown') ?>
<?= CHtml::dropDownList('layout-dropdown', null, $listLayout); ?>
<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-printer']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $("#tombol-submit-printer").click(function () {
        var printer = $("#printer-dropdown").val();
        var layout = $("#layout-dropdown").val();
        var data = $('#label-barang-cetak-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxprinter': true,
            'printer-id': printer,
            'layout-id': layout,
            'items': data
        };
        var dataUrl = '<?= $this->createUrl('cetaklabel'); ?>';
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.itemCount + ' item, ' + data.labelCount + ' label dicetak',
                        time: 3000
                    });
                    $('#label-barang-cetak-grid').yiiGridView('update');
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 5000
                    });
                }
                $('#pilih-printer-m').foundation('reveal', 'close');
            }
        });
    });
</script>
