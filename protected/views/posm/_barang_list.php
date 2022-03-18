<?php
$this->widget('BGridView', [
    'id'            => 'barang-grid',
    'dataProvider'  => $barang->search(20),
    'enableSorting' => false,
    //'filter' => $penjualanDetail,
    'template' => '{items}{pager}{summary}',
    'columns'  => [
        [
            'name'  => 'namaBarang',
            'type'  => 'raw',
            'value' => [$this, 'renderBarangList'],
        ],
        [
            'header'            => 'Stok',
            'value'             => '$data->stok',
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan']
        ],
        [
            'class'       => 'BButtonColumn',
            'htmlOptions' => ['style' => 'text-align:center'],
            // Pakai template delete untuk pilih :) biar gampang
            'template'             => '{nonaktif} {delete}',
            'deleteButtonUrl'      => '$data->barcode',
            'deleteButtonImageUrl' => false,
            'deleteButtonLabel'    => '<i class="fa fa-check"></i> Pilih',
            'deleteButtonOptions'  => ['title' => 'Pilih', 'class' => 'pilih'],
            'deleteConfirmation'   => false,
            'buttons'              => [
                'delete' => [
                    'visible' => '$data->status', // Visible jika barang aktif
                ],
                'nonaktif' => [
                    'visible' => '!$data->status', // Visible jika barang non aktif
                    'label'   => 'Non Aktif',
                    'url'     => '"#"',
                    'options' => ['class' => 'delete']
                ]
            ],
        ],
    ],
]);
?>
<script>
    <?php
    /*
 * Memberi class berbeda pada baris yang disorot
 */
    ?>
    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });

    <?php
    /*
 * Jika dipilih, nilai barcode diambil. Tambah barang
 */
    ?>
    $("body").on("click", "a.pilih", function() {
        //console.log($(this).attr("href"));
        var barcode = $(this).attr("href");

        $("#barang-list").hide(0, function() {
            $("#transaksi").show(0, function() {
                $("#scan").val(barcode);
                kirimBarcode();
            });

        });
        return false;
    });
</script>