<?php
/**
 * @var Pembelian $pembelian
 * @var PembelianDetail $pembelianDetail
 * @var boolean $pilihBarang
 * @var integer $tipeCari
 */
// Bisa Edit Qty jika masih draft
if ($pembelian->status == Pembelian::STATUS_DRAFT) :
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
endif;
?>

<div class="small-12  columns">
    <?php
    $this->widget('BGridView', [
        'id'           => 'pembelian-detail-grid',
        'dataProvider' => $pembelianDetail->search($pilihBarang ? null : 't.id'),
        //'filter' => $pembelianDetail,
        'rowCssClassExpression' => function ($row, $data) {
            if ($data->isBarangBaru()) {
                return 'baru';
            } elseif ($data->isMarginMin()) {
                return 'margin-min';
            } elseif ($data->isHargaJualBerubah()) {
                return 'hj-berubah';
            }
        },
        'summaryText' => '{start}-{end} dari {count}, Total: <span class="label-total">' . $pembelian->total . '</span>',
        'columns'     => [
            [
                'name'  => 'barcode',
                'value' => '$data->barang->barcode',
            ],
            [
                'name'  => 'namaBarang',
                'value' => '$data->barang->nama',
            ],
            [
                'name'  => 'qty',
                'value' => function ($data) {
                    return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                        $data->qty . '</a>';
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
            ],
            [
                'name'              => 'harga_beli',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_beli, 0, ",", ".")'
            ],
            [
                'name'              => 'harga_jual',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'value'             => 'number_format($data->harga_jual, 0, ",", ".")'
            ],
            [
                'name'              => 'subTotal',
                'header'            => 'Total',
                'value'             => '$data->total',
                'headerHtmlOptions' => ['class' => 'rata-kanan'],
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'filter'            => false
            ],
            [
                'header' => 'Rak=NULL',
                'value'  => function ($data) {
                    if (is_null($data->barang->rak_id)) {
                        return '<a href="#" class="editable-rak" data-type="select" data-pk="' . $data->barang_id . '" data-url="' . Yii::app()->controller->createUrl('updaterak') . '">NULL</a>';
                    } else {
                        /* Uncomment jika ingin ditampilkan nama rak */
                        //return $data->barang->rak->nama;
                    }
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['class' => 'rata-tengah'],
                'htmlOptions'       => ['class' => 'rata-tengah'],
            ],
            [
                'header'            => 'Edit',
                'value'             => function ($data) {
                    return '<a href="#" data-reveal-id="edit-modal" class="tombol-edit-detail" data-pk="' . $data->id . '"><i class="fa fa-edit"></i></a>';
                },
                'type'              => 'raw',
                'headerHtmlOptions' => ['class' => 'rata-tengah'],
                'htmlOptions'       => ['class' => 'rata-tengah'],
            ],
            // Jika pembelian masih draft tampilkan tombol hapus
            [
                'class'           => 'BButtonColumn',
                'template'        => $pembelian->status == 0 ? '{delete}' : '',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("pembelian/hapusdetail", array("id"=>$data->primaryKey))',
                'afterDelete'     => 'function(link,success,data){ if(success) updateTotal(); }',
            ],
        ],
    ]);
    ?>
</div>
<div id="edit-modal" class="reveal-modal" data-reveal aria-labelledby="Edit Detail" aria-hidden="true" role="dialog" style="padding:0">
    <?php
    $this->renderPartial('_edit_pemb_detail', [
        'pembelianModel' => $pembelian,
        'tipeCari'       => $tipeCari,
    ]);
    ?>
</div>
<script>
 /**
     * Update nilai-nilai pada form edit detail barang
     * @param json Informasi barang
     * @returns {mixed} Menampilkan form detail edit barang dan mengisi field yang diperlukan
     */
    function updateFormEditDetail(info) {
        infoBarangEdit = info;
        $(".response").html("");
        $(".response").hide();
        if (!info['sukses']) {
            $.gritter.add({
                title: 'Error ' + info.error.code,
                text: info.error.msg,
                time: 5000,
            });
            $("#scan").focus();
            return false;
        }
        if (!info['termasuk']) {
            $(".response").show();
            $(".response").html("Barang TIDAK terdaftar di SUPPLIER ini! Jika diinput akan OTOMATIS DITAMBAHKAN ke supplier ini")
        }
        $("#edit-barang-info").html(info['nama'] + ' <small>' + info['barcode'] + '</small>');
        $("#edit-detail-id").val(info['detailId']);
        $("#edit-barang-id").val(info['barangId']);
        $("#edit-edit-label-harga-beli").text('Harga Beli (' + info['labelHargaBeli'] + ')');
        $("#edit-harga-beli").val(info['hargaBeli']);
        $("#edit-label-harga-jual").text('Harga Jual (' + info['labelHargaJual'] + ')');
        $("#edit-harga-jual").val(info['hargaJual']);
        $("#edit-satuan").text(info['satuan']);
        $("#edit-qty").val(info['qty']);
        $("#subtotal").val('');
        $("#edit-qty").focus();
        $("#edit-qty").select();
        $("#edit-harga-jual-raw").html('&nbsp;');
        $("#scan").val("");

        $(".input-shj").val('');
        info['skemaHJ'].forEach(function(skema) {
            console.log(skema);
            $("#skemahj_" + skema['id']).val(skema['harga']);
        });
    }

    $(document).on('click', ".tombol-edit-detail", function() {
        var detailId = $(this).data("pk");
        console.log(detailId);
        var datakirim = {
            'detailId': detailId
        };
        var dataurl = "<?php echo $this->createUrl('getdetailbarang', ['id' => $pembelian->id]) ?>";

        $.ajax({
            data: datakirim,
            url: dataurl,
            type: "POST",
            dataType: "json",
            success: updateFormEditDetail
        });
    })

    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pembelian-detail-grid");
                    updateTotal();
                }
            }
        });
        $(".editable-rak").editable({
            mode: "inline",
            //inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("pembelian-detail-grid");
                }
            },
            source: [
                <?php
                $listRak  = CHtml::listData(RakBarang::model()->findAll(['select' => 'id,nama', 'order' => 'nama']), 'id', 'nama');
                $firstRow = true;
                foreach ($listRak as $key => $value) :
                ?>
                    <?php
                    if (!$firstRow) {
                        echo ',';
                    }
                    $firstRow = false;
                    ?> {
                        value: <?php echo $key; ?>,
                        text: "<?php echo $value; ?>"
                    }
                <?php
                endforeach;
                ?>
            ]
        });
    }
    $(function() {
        enableEditable();
    });
    $(document).ajaxComplete(function() {
        enableEditable();
    });
</script>