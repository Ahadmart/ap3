<?php
$this->widget('BGridView',
        [
    'id'            => 'barang-grid',
    'dataProvider'  => $barang->search(20),
    'enableSorting' => false,
    'filter'        => $barang,
    'columns'       => ['barcode',
        'nama',
        // [
        //     'header'            => 'RRP',
        //     'value'             => '$data->hargaJualRekomendasi',
        //     'headerHtmlOptions' => ['class' => 'rata-kanan'],
        //     'htmlOptions'       => ['class' => 'rata-kanan']
        // ],
        [
            'header'            => 'Stok',
            'value'             => '$data->stok',
            'headerHtmlOptions' => ['class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan']
        ],
        [
            'header'            => 'Tambahkan',
            'type'              => 'raw',
            'value'             => [$this, 'renderTombolTambahkanBarang'],
            'headerHtmlOptions' => ['class' => 'rata-tengah'],
            'htmlOptions'       => ['class' => 'rata-tengah'],
        ],
    ],
]);
?>

<script>
    $(function () {
        $(document).on('click', ".tombol-tambahkan-barang", function () {
            dataUrl = $(this).attr('href');
            dataKirim = {barangId: $(this).data('barangid')};
            //console.log(dataUrl);

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        updateGrid();
                    }
                }
            });
            return false;
        });
    });
</script>