<?php
$this->widget('BGridView', [
    'id'            => 'inventory-balance-grid',
    'dataProvider'  => $inventoryBalance->search('t.id'), // order by id asc
    'enableSorting' => false,
    'emptyText'     => 'Stok kosong',
    'columns'       => [
        //'asal',
        'namaProfilPembelian',
        'nomor_dokumen',
        'harga_beli',
        'qty',
        [
            'header' => 'Pilih',
            'type'   => 'raw',
            'value'  => [$this, 'renderRadioButton'],
        ],
    ],
]);
?>
<script>
    $("body").on("focusin", "a.pilih", function() {
        $(this).parent('td').parent('tr').addClass('pilih');
    });

    $("body").on("focusout", "a.pilih", function() {
        $(this).parent('td').parent('tr').removeClass('pilih');
    });
</script>