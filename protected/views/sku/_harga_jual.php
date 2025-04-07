<?= CHtml::link('&#215;', '', ['class' => 'close-reveal-modal', 'aria-label' => 'Close']) ?>
<h4><small>Update</small> Harga Jual</h4>
<hr />
<?php
$form = $this->beginWidget('CActiveForm', [
    'id'                   => 'harga-jual-form',
    'enableAjaxValidation' => false,
]);
?>
<div class="row">
    <div class="small-12 columns">
        <div class="row collapse">
            <div class="medium-9 columns">
                <input id="hj-input" type="text" name="hj" />
            </div>
            <div class="medium-3 columns">
                <?= CHtml::link('Update', '#', ['class' => 'tiny bigfont button postfix', 'id' => 'tombol-update-hj']) ?>
                <?php
                // echo CHtml::ajaxSubmitButton('Update', $this->createUrl('barang/updatehargajual', ['id' => $barang->id]), [
                //     'success' => "function () {
                //                 $.fn.yiiGridView.update('harga-jual-grid');
                //             }",
                // ], [
                //     'class' => 'button postfix',
                //     'id'    => 'tombol-update-hj'
                // ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<?php
$this->widget('BGridView', [
    'id'           => 'harga-jual-grid',
    'dataProvider' => $hargaJual->search(),
    'columns'      => [
        [
            'name'              => 'harga',
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan'],
        ],
        'created_at',
    ],
]);
?>

<script>
    $(document).on('opened.fndtn.reveal', '#tambahhj-form[data-reveal]', function() {
        var modal = $(this);
        // console.log("Ganti rak opened");
        $("#hj-input").focus();
    });

    $("#harga-jual-form").submit(function(e) {
        e.preventDefault(); // Mencegah form terkirim saat tekan Enter
    });

    $("#hj-input").keyup(function(e) {
        console.log("Keyup detected: " + e.key);
        if (e.keyCode === 13) {
            e.preventDefault();
            // console.log("Enter pressed, clicking button...");
            $("#tombol-update-hj").click();
        }
        return false;
    });

    $("#tombol-update-hj").click(function() {
        var hj = $("#hj-input").val();
        var dataKirim = {
            'barang-id': <?= $barang->id ?>,
            'hj': hj
        };
        var dataUrl = '<?= $this->createUrl('updatehj'); ?>';
        // console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function(data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: 'Update harga jual berhasil',
                        time: 3000
                    });
                    $('#sku-detail-grid').yiiGridView('update');
                } else {
                    // $.gritter.add({
                    //     title: 'Error ' + data.error.code,
                    //     text: data.error.msg,
                    //     time: 5000
                    // });
                }
                $('#tambahhj-form').foundation('reveal', 'close');
            }
        });
    });
</script>