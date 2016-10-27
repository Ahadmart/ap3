<?php
$this->widget('BGridView', array(
    'id' => 'inventory-balance-grid',
    'dataProvider' => $inventoryBalance->search('t.id'), // order by id asc
    'enableSorting' => false,
    'emptyText' => 'Stok kosong',
    'columns' => array(
        //'asal',
        'nomor_dokumen',
        'harga_beli',
        'qty',
        array(
            'header' => 'Pilih',
            'type' => 'raw',
            'value' => array($this, 'renderRadioButton')
        )
    ),
));
?>
<script>
   $("body").on("focusin", "a.pilih", function () {
      $(this).parent('td').parent('tr').addClass('pilih');
   });

   $("body").on("focusout", "a.pilih", function () {
      $(this).parent('td').parent('tr').removeClass('pilih');
   });
</script>