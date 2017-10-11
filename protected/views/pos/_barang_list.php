<?php
$this->widget('BGridView', array(
    'id' => 'barang-grid',
    'dataProvider' => $barang->search(20),
    'enableSorting' => false,
    //'filter' => $penjualanDetail,
    'template' => '{items}{pager}{summary}',
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
            'template' => '{nonaktif} {delete}',
            'deleteButtonUrl' => '$data->barcode',
            'deleteButtonImageUrl' => false,
            'deleteButtonLabel' => '<i class="fa fa-check"></i> Pilih',
            'deleteButtonOptions' => array('title' => 'Pilih', 'class' => 'pilih'),
            'deleteConfirmation' => false,
            'buttons' => array(
                'delete' => array(
                    'visible' => '$data->status', // Visible jika barang aktif
                ),
                'nonaktif' => [
                    'visible' => '!$data->status', // Visible jika barang non aktif
                    'label' => 'Non Aktif',
                    'url' => '"#"',
                    'options' => ['class'=>'delete']
                ]
            ),
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
 * Jika dipilih, nilai barcode diambil. Tambah barang
 */
?>
    $("body").on("click", "a.pilih", function () {
        //console.log($(this).attr("href"));
        var barcode = $(this).attr("href");

        $("#barang-list").hide(0, function () {
            $("#transaksi").show(0, function () {
                $("#scan").val(barcode);
                kirimBarcode();
            });

        });
        return false;
    });
</script>