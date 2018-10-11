<?php
$this->widget('BGridView', array(
    'id' => 'barang-grid',
    'dataProvider' => $barang->search(20, $curSupplierCr),
    'enableSorting' => false,
    //'filter' => $penjualanDetail,
    'columns' => array(
        'barcode',
        'nama',
        array(
            'header' => 'Harga Jual',
            'value' => '$data->hargaJual',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
        array(
            'header' => 'Stok',
            'value' => '$data->stok',
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan')
        ),
        array(
            'class' => 'BButtonColumn',
            'htmlOptions' => array('style' => 'text-align:center'),
            // Pakai template delete untuk pilih :) biar gampang
            'deleteButtonUrl' => '$data->id',
            'deleteButtonImageUrl' => false,
            'deleteButtonLabel' => '<i class="fa fa-check"></i> Pilih',
            'deleteButtonOptions' => array('title' => 'Pilih', 'class' => 'pilih'),
            'deleteConfirmation' => false,
        ),
    ),
));
?>
<script>
<?php
/*
 * Memberi class berbeda pada baris yang disorot
 */
?>
    $("body").on("focusin", "a.pilih", function () {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function () {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });

<?php
/*
 * Jika dipilih, nilai barcode diambil, cursor ke qty
 */
?>
    $("body").on("click", "a.pilih", function () {
        //console.log($(this).attr("href"));
        var barangId = $(this).attr("href");

        //$("#barang-list").hide(100, function () {
        var datakirim = {
            'barangId': barangId
        };
        var dataurl = "<?php echo $this->createUrl('getbarang',['id'=>$poModel->id]) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            dataType: "json",
            success: function(data) {
                if (data.sukses) {
                    updateFormDetail(data.info)
                } else {
                    $(".response").addClass("error");
                    $(".response").html("Error : <br />" + data.error.msg).slideDown('slow').delay(5000).slideUp('slow');
                }
            }
        });

        //});
        return false;
    });
</script>