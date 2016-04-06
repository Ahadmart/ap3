<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'barang-grid',
            'dataProvider' => $barangBelumSO->search(),
            'filter' => $barangBelumSO,
            'itemsCssClass' => 'tabel-index responsive',
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'barcode',
                    'header' => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                ),
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                ),
                array(
                    'name' => 'Stok',
                    'value' => '$data->stok',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false
                ),
                /*
                array(
                    'class' => 'BButtonColumn',
                ),
                 * 
                 */
            ),
        ));
        ?>
    </div>
</div>
<hr />