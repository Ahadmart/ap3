<div class="small-12  columns">
    <?php
    $this->widget('BGridView',
            [
        'id'            => 'barang-grid',
        'dataProvider'  => $barang->search(20),
        'enableSorting' => false,
        'columns'       => [
            'barcode',
            'nama',
            [
                'header'            => 'Harga Jual',
                'value'             => '$data->hargaJual',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan']
            ],
            [
                'header'            => 'Stok',
                'value'             => '$data->stok',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan']
            ],
            [
                'class'                => 'BButtonColumn',
                'htmlOptions'          => ['style' => 'text-align:center'],
                'deleteButtonUrl'      => '$data->barcode',
                'deleteButtonImageUrl' => false,
                'deleteButtonLabel'    => '<i class="fa fa-check"></i>',
                'deleteButtonOptions'  => ['title' => 'Pilih', 'class' => 'pilih'],
                'deleteConfirmation'   => false,
            ],
        ],
    ]);
    ?>
</div>
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
        var barcode = $(this).attr("href");

        $("#barang-list").hide(100, function () {
            $("#sales-order-detail").show(100, function () {
                $("#scan").val(barcode);
                $("#qty").focus();
                $("#qty").select();
            });

        });
        return false;
    });
</script>