<?= CHtml::label('Tambahkan supplier', 'sup-dropdown-t') ?>
<?= CHtml::dropDownList('sup-dropdown-t', null, CHtml::listData(Profil::model()->tipeSupplier()->profilTrx()->findAll(array('order' => 'nama')), 'id', 'nama'), ["prompt" => "Pilih satu.."]); ?>
<!--<p>ATAU</p>-->
<?php // CHtml::label('Ganti supplier (Supplier yang sudah ada akan dihapus!)', 'sup-dropdown-g') ?>
<?php // CHtml::dropDownList('sup-dropdown-g', null, CHtml::listData(Profil::model()->tipeSupplier()->profilTrx()->findAll(array('order' => 'nama')), 'id', 'nama'), ["prompt" => "Pilih satu.."]); ?>

<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-supplier']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $("#sup-dropdown-t").change(function () {
        var value = $(this).val();
        if (value.length > 0) {
            $("#sup-dropdown-g").val("");
        }
    });
    $("#sup-dropdown-g").change(function () {
        var value = $(this).val();
        if (value.length > 0) {
            $("#sup-dropdown-t").val("");
        }
    });
    $("#tombol-submit-supplier").click(function () {
        var supT = $("#sup-dropdown-t").val();
        var supG = $("#sup-dropdown-g").val();
        if (supT.length === 0 && supG.length === 0) {
            $('#edit-sup-m').foundation('reveal', 'close');
            console.log("Edit Supplier Modal diclose");
            return false;
        }
        var dataUrl = "";
        var sup = "";
        var ket = "";
        if (supT.length > 0) {
            dataUrl = "<?= $this->createUrl('tambahsup'); ?>";
            sup = supT;
            ket = "telah ditambah supplier";
        } else if (supG.length > 0) {
            dataUrl = "<?= $this->createUrl('gantisup'); ?>";
            sup = supG;
            ket = "telah diganti suppliernya dengan";
        }
        var data = $('#barang-grid').yiiGridView('getChecked', 'kolomcek');
        var dataKirim = {
            'ajaxsup': true,
            'sup-id': sup,
            'items': data
        };
        console.log(dataUrl+' === '+dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' item ' + ket + ': ' + data.namasup,
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
                $('#edit-sup-m').foundation('reveal', 'close');
            }
        });
    });
</script>
