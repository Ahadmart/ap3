<?php
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

$this->widget('BGridView', array(
    'id' => 'label-rak-cetak-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'itemsCssClass' => 'tabel-index',
    'columns' => array(
        array(
            'name' => 'barcode',
            'value' => '$data->barang->barcode',
            'filter' => false,
        ),
        array(
            'name' => 'namaBarang',
            'value' => '$data->barang->nama',
            'filter' => false,
        ),
        array(
            'name' => 'kategoriId',
            'value' => 'isset($data->barang->kategori) ? $data->barang->kategori->nama : ""',
            'filter' => LabelRakCetak::model()->filterKategori()
        ),
        array(
            'header' => 'Satuan',
            'value' => '$data->barang->satuan->nama'
        ),
        array(
            'header' => 'Harga Jual',
            'value' => '$data->barang->hargajual',
            'htmlOptions' => array('class' => 'rata-kanan'),
            'headerHtmlOptions' => array('class' => 'rata-kanan'),
        ),
        array(
            'class' => 'BButtonColumn',
            'header' => '<a id="tombol-hapus-semua" href="' . $this->createUrl('hapussemua') . '"><i class="fa fa-times"></i></a>'
        ),
    ),
));
?>
<script>
    $("body").on("click", "#tombol-hapus-semua", function() {
        var dataurl = $(this).attr('href');
        $.ajax({
            url: dataurl,
            success: function() {
                $.fn.yiiGridView.update('label-rak-cetak-grid')
            }
        });
        return false;
    });
</script>