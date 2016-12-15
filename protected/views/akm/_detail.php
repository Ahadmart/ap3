<?php
$this->widget('BGridView', array(
    'id' => 'akm-detail-grid',
    'dataProvider' => $akmDetail->search(),
    'summaryText' => 'Poin struk ini: ' . $akm->getCurPoin() . ' | Poin sebelumnya: ' . $akm->getTotalPoinPeriodeBerjalan() . ' | {start}-{end} dari {count}',
    'itemsCssClass' => 'tabel-index responsive',
    'template' => '{items}{summary}{pager}',
    'enableSorting' => false,
    'columns' => array(
        array(
            'name' => 'barcode',
            'value' => '$data->barang->barcode',
            'htmlOptions' => array('class' => 'barcode'),
        ),
        array(
            'type' => 'raw',
            'value' => function($data) {
                $tombol = '<a><i class="fa fa-times fa-4x"></i></a>';
                return $tombol;
            },
            //'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
            'htmlOptions' => array('style' => 'padding-left:0'),
        ),
        array(
            'name' => 'namaBarang',
            'value' => '$data->barang->nama',
        //'htmlOptions' => array('class' => 'barcode'),
        ),
        array(
            'name' => 'harga_jual',
            'headerHtmlOptions' => array('class' => 'rata-kanan show-for-large-up'),
            'htmlOptions' => array('class' => 'rata-kanan show-for-large-up'),
            'value' => function($data) {
        return rtrim(rtrim(number_format($data->harga_jual + $data->diskon, 2, ',', '.'), '0'), ',');
    }
        ),
        array(
            'name' => 'diskon',
            'header' => 'Diskon',
            'headerHtmlOptions' => array('class' => 'rata-kanan show-for-large-up'),
            'htmlOptions' => array('class' => 'rata-kanan show-for-large-up'),
            'value' => function($data) {
        return rtrim(rtrim(number_format($data->diskon, 2, ',', '.'), '0'), ',');
    }
        ),
        //<a class="button" href="/ahadpos3/barang/tambah"><i class="fa fa-plus"></i></a>
        array(
            'name' => 'qty',
            'header' => '<span class="ak">Q</span>ty',
            'type' => 'raw',
            //'value' => array($this, 'renderQtyWButton'),
            'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
            'htmlOptions' => array('class' => 'rata-kanan'),
        ),
        /*
          array(
          'type' => 'raw',
          'value' => '"<span class=\"secondary label\">".$data->barang->satuan->nama."</label>"',
          'htmlOptions' => array('style' => 'padding-left:0'),
          ), */
        array(
            'type' => 'raw',
            'value' => function($data) {
                $tombolMin = '<a class="button tombol ubahqty" href="' . $this->createUrl('qtymin', ['id' => $data->id]) . '"><i class="fa fa-minus"></i></a>';
                $tombolPlus = '<a class="warning button tombol ubahqty" href="' . $this->createUrl('qtyplus', ['id' => $data->id]) . '"><i class="fa fa-plus"></i></a>';
                return $tombolPlus . $tombolMin;
            },
                    'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
                    'htmlOptions' => array('style' => 'padding-left:0'),
                ),
                array(
                    'name' => 'subTotal',
                    'header' => 'Total',
                    'value' => '$data->harga_jual * $data->qty',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false
                ),
            ),
        ));
        ?>
<script>
    
</script>