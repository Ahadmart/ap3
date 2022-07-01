<?= CHtml::label('Tambahkan supplier', 'sup-dropdown-t') ?>
<?= CHtml::dropDownList('sup-dropdown-t', null, CHtml::listData(Profil::model()->tipeSupplier()->profilTrx()->findAll(array('order' => 'nama')), 'id', 'nama'), ["prompt" => "Pilih satu.."]); ?>
<label>Set sebagai supplier default</label>
<div class="small switch">
    <?= CHtml::checkBox("set_default", false) ?>
    <?= CHtml::label("Set default", 'set_default') ?>
</div>
<p>ATAU</p>
<?= CHtml::label('Ganti supplier (Supplier yang sudah ada akan dihapus!)', 'sup-dropdown-g') ?>
<?= CHtml::dropDownList('sup-dropdown-g', null, CHtml::listData(Profil::model()->tipeSupplier()->profilTrx()->findAll(array('order' => 'nama')), 'id', 'nama'), ["prompt" => "Pilih satu.."]); ?>

<?= CHtml::link('Submit', '#', ['class' => 'tiny bigfont button', 'id' => 'tombol-submit-supplier']) ?>
<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>

<script>
    $(document).on('opened.fndtn.reveal', '#edit-sup-m[data-reveal]', function() {
        var modal = $(this);
        $("#sup-dropdown-t").focus();
    });
    $("#sup-dropdown-t").change(function() {
        var value = $(this).val();
        if (value.length > 0) {
            $("#sup-dropdown-g").val("");
        }
    });
    $("#sup-dropdown-g").change(function() {
        var value = $(this).val();
        if (value.length > 0) {
            $("#sup-dropdown-t").val("");
        }
    });
    $("#tombol-submit-supplier").click(function() {
        var supT = $("#sup-dropdown-t").val();
        var supG = $("#sup-dropdown-g").val();
        var supDefault = $("#set_default").prop('checked') ? 1 : 0;
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
            'sup-def': supDefault,
            'items': data
        };
        console.log(dataUrl + ' === ' + dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' item ' + ket + ': ' + data.namasup,
                        time: 5000
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