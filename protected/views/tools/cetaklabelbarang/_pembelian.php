<?php
$this->widget('BGridView', ['id' => 'pembelian-grid',
    'dataProvider'               => $model->search(),
    'filter'                     => $model,
    'itemsCssClass'              => 'tabel-index',
    'columns'                    => [
        [
            'class'     => 'BDataColumn',
            'name'      => 'nomor',
            'header'    => '<span class="ak">N</span>omor',
            'accesskey' => 'n',
            'type'      => 'raw',
            'value'     => [$this, 'renderPembelianLinkToView']
        ],
        [
            'class'     => 'BDataColumn',
            'name'      => 'tanggal',
            'header'    => 'Tangga<span class="ak">l</span>',
            'accesskey' => 'l',
            'type'      => 'raw',
        ],
        [
            'name'  => 'namaSupplier',
            'value' => '$data->profil->nama'
        ],
        [
            'name'  => 'namaUpdatedBy',
            'value' => '$data->updatedBy->nama_lengkap',
        ],
        [
            'header'      => 'Total',
            'value'       => '$data->total',
            'htmlOptions' => ['class' => 'rata-kanan']
        ],
        [
            'header'            => 'Tambahkan',
            'type'              => 'raw',
            'value'             => [$this, 'renderTombolTambahkan'],
            'headerHtmlOptions' => ['class' => 'rata-tengah'],
            'htmlOptions'       => ['class' => 'rata-tengah'],
        ],
    ],
]);
?>
<script>
    $(function () {
        $(document).on('click', ".tombol-tambahkan", function () {
            dataUrl = $(this).attr('href');
            dataKirim = {pembelianId: $(this).data('pembelianid')};
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