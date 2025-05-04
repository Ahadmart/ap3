<div id="hapus-detail-form" class="small reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
    <h2 id="modalTitle">Konfirmasi Hapus</h2>
    <label>Alasan penghapusan:
        <input type="text" id="alasan-hapus-detail">
    </label>
    <div class="text-right">
        <a href="#" class="small bigfont tiny button alert" id="hapus-detail-submit">OK</a>
        <a class="close-reveal-modal">&#215;</a>
    </div>
</div>

<div class="small-12  columns">
    <?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

    $this->widget('BGridView', [
        'id'            => 'penjualan-detail-grid',
        'dataProvider'  => $penjualanDetail->search(),
        'itemsCssClass' => 'tabel-index responsive',
        //'filter' => $penjualanDetail,
        'columns'       => [
            [
                'name'  => 'barcode',
                'value' => '$data->barang->barcode',
            ],
            [
                'name'  => 'namaBarang',
                'value' => '$data->barang->nama',
            ],
            [
                'header'            => 'Stok',
                'value'             => '$data->barang->stok',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'qty',
                'headerHtmlOptions' => ['style' => 'width:75px;', 'class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            //			  array(
            //					'name' => 'harga_beli',
            //					'htmlOptions' => array('class' => 'rata-kanan'),
            //					'value' => function($data) {
            //			 return number_format($data->harga_beli, 0, ',', '.');
            //		 }
            //			  ),
            [
                'name'              => 'harga_jual',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => function ($data) {
                    return number_format($data->harga_jual, 0, ',', '.');
                },
            ],
            [
                'name'              => 'harga_jual_rekomendasi',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => function ($data) {
                    return number_format($data->harga_jual_rekomendasi, 0, ',', '.');
                },
            ],
            [
                'name'              => 'subTotal',
                'value'             => '$data->total',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false,
            ],
            // Jika penjualan masih draft tampilkan tombol hapus
            [
                'class'              => 'BButtonColumn',
                'template'           => $penjualan->status == 0 ? '{delete}' : '',
                'deleteButtonUrl'    => 'Yii::app()->controller->createUrl("penjualan/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete'        => 'function(link,success,data){ if(success) konfirmasiHapus(data);}',
                'deleteConfirmation' => $konfirmasiHapusDetail ? false : 'Anda yakin?',
            ],
        ],
    ]);
    ?>
</div>

<script>
    function konfirmasiHapus(data) {
        try {
            if (data.konfirmasi == true) {
                // Show modal
                $('#hapus-detail-form').one('opened.fndtn.reveal', function() {
                    $('#alasan-hapus-detail').val('').focus();
                });
                $('#hapus-detail-form').foundation('reveal', 'open');
                $('#alasan-hapus-detail').val('').focus(); // Reset and focus input

                // Set one-time click event for OK button
                $('#hapus-detail-submit').off('click').on('click', function(e) {
                    e.preventDefault();
                    var alasan = $('#alasan-hapus-detail').val();
                    var dataKirim = {
                        id: data.id,
                        alasan: alasan
                    };

                    $.ajax({
                        url: '<?php echo $this->createUrl('hapusdetailkonfirmasi') ?>',
                        type: 'POST',
                        data: dataKirim,
                        success: function(res) {
                            console.log("Alasan terkirim:", res);
                            if (res.sukses === false) {
                                $.gritter.add({
                                    title: 'Error ' + res.error.code,
                                    text: res.error.msg,
                                    time: 3000,
                                });
                            }
                            // Optional: update grid/UI
                            $.fn.yiiGridView.update("penjualan-detail-grid");
                            updateTotal();
                        },
                        error: function() {
                            alert("Gagal mengirim alasan.");
                        }
                    });

                    $('#hapus-detail-form').foundation('reveal', 'close');
                })
            }
        } catch (e) {

        }
    }

    $("#alasan-hapus-detail").keydown(function(e) {
        if (e.keyCode === 13) {
            $("#hapus-detail-submit").click();
        }
    });
</script>