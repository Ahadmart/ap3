<div id="pilih-printer-m" class="tiny reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog"></div>

<?= CHtml::link('Cetak', '#', ['class' => 'button tb-aksi', 'id' => 'tombol-cetak', 'disabled' => true]) ?>

<?= CHtml::link('Hapus', '#', ['class' => 'button tb-aksi', 'id' => 'tombol-hapus', 'disabled' => true]) ?>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js',
        CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js',
        CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');

$this->widget('BGridView',
        [
    'id'           => 'label-barang-cetak-grid',
    'dataProvider' => $labelBarang->search(),
    'filter'       => $labelBarang,
    'columns'      => [
        [
            'id'             => 'kolomcek',
            'class'          => 'CCheckBoxColumn',
            'selectableRows' => 2,
            'value'          => '$data->id'
        ],
        [
            'name'  => 'barcode',
            'value' => '$data->barang->barcode'
        ],
        [
            'name'  => 'namaBarang',
            'value' => '$data->barang->nama'
        ],
        [
            'name'  => 'qty',
            'value' => function($data) {
                return '<a href="#" class="editable-qty" data-type="text" data-pk="' . $data->id . '" data-url="' . Yii::app()->controller->createUrl('updateqty') . '">' .
                        $data->qty . '</a>';
            },
            'type'              => 'raw',
            'headerHtmlOptions' => ['style' => 'width:75px', 'class' => 'rata-kanan'],
            'htmlOptions'       => ['class' => 'rata-kanan'],
        ],
    ],
]);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js',
        CClientScript::POS_HEAD);
?>
<script>
    $(document).ajaxComplete(function () {
        cekboxchange();
        $(".checkbox-column").change(cekboxchange);
    });

    $(".checkbox-column").change(cekboxchange);

    function cekboxchange() {
        var data = $('#label-barang-cetak-grid').yiiGridView('getChecked', 'kolomcek');
        console.log(data.length);
        DisableTombol();
        if (data.length > 0) {
            enableTombol();
        }
    }

    function enableTombol() {
        $(".tb-aksi").attr("disabled", false);
    }

    function DisableTombol() {
        $(".tb-aksi").attr("disabled", true);
    }

    $("#tombol-hapus").click(function () {
        if ($(this).is("[disabled]")) {
            // console.log('hapus disabled clicked');
        } else {
            var dataUrl = '<?= $this->createUrl('hapus'); ?>';
            var data = $('#label-barang-cetak-grid').yiiGridView('getChecked', 'kolomcek');
            var dataKirim = {
                'ajaxdata': true,
                'items': data
            };
            console.log(dataKirim);
            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function (data) {
                    if (data.sukses) {
                        $.gritter.add({
                            title: 'Sukses',
                            text: data.rowAffected + ' item dihapus',
                            time: 3000
                        });
                        $('#label-barang-cetak-grid').yiiGridView('update');
                    } else {
                        $.gritter.add({
                            title: 'Error ' + data.error.code,
                            text: data.error.msg,
                            time: 5000
                        });
                    }
                }
            });
        }
    });

    $("#tombol-cetak").click(function () {
        if ($(this).is("[disabled]")) {
            console.log('cetak disabled clicked');
        } else {
            $('#pilih-printer-m').foundation('reveal', 'open', {
                url: '<?= $this->createUrl('formpilihprinter') ?>',
                success: function (data) {
                    // Tampilkan Dropdown pilihan printer
                },
                error: function () {
                    alert('Gagal mengambil data printer!');
                }
            });
        }
    });

    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("label-barang-cetak-grid");
                }
            }
        });
    }

    $(function () {
        enableEditable();
    });
    $(document).ajaxComplete(function () {
        enableEditable();
    });
</script>