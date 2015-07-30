<div class="small-12  columns">
    <?php
    $this->widget('BGridView', array(
        'id' => 'barang-grid',
        'dataProvider' => $barang->search(20),
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
//			  array(
//					'name' => 'qty',
//					'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
//					'htmlOptions' => array('class' => 'rata-kanan'),
//			  ),
//			  array(
//					'name' => 'harga_beli',
//					'htmlOptions' => array('class' => 'rata-kanan'),
//					'value' => function($data) {
//			 return number_format($data->harga_beli, 0, ',', '.');
//		 }
//			  ),
//			  array(
//					'name' => 'harga_jual',
//					'headerHtmlOptions' => array('class' => 'rata-kanan'),
//					'htmlOptions' => array('class' => 'rata-kanan'),
//					'value' => function($data) {
//			 return number_format($data->harga_jual, 0, ',', '.');
//		 }
//			  ),
//			  array(
//					'name' => 'subTotal',
//					'value' => '$data->total',
//					'headerHtmlOptions' => array('class' => 'rata-kanan'),
//					'htmlOptions' => array('class' => 'rata-kanan'),
//					'filter' => false
//			  ),
            // Jika penjualan masih draft tampilkan tombol hapus
            array(
                'class' => 'BButtonColumn',
                'htmlOptions' => array('style' => 'text-align:center'),
                // Pakai template delete untuk pilih :) biar gampang
                'deleteButtonUrl' => '$data->barcode',
//					'afterDelete' => 'function(link,success,data){ if(success) updateTotal();}'
                'deleteButtonImageUrl' => false,
                'deleteButtonLabel' => '<i class="fa fa-check"></i>',
                'deleteButtonOptions' => array('title' => 'Pilih', 'class' => 'pilih'),
                'deleteConfirmation' => false,
            ),
        ),
    ));
    ?>
</div>
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
 * Jika dipilih, nilai barcode diambil, cursor ke qty
 */
?>
    $("body").on("click", "a.pilih", function() {
        //console.log($(this).attr("href"));
        var barcode = $(this).attr("href");

        $("#barang-list").hide(100, function() {
            $("#retur-penjualan-detail").show(100, function() {
                $("#scan").val(barcode);
                $("#qty").focus();
                $("#qty").select();
            });

        });
        return false;
    });
</script>