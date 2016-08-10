<?php
/* @var $this DiskonbarangController */
/* @var $model DiskonBarang */

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
    'Diskon Barang' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Diskon Barang';
$this->boxHeader['normal'] = 'Diskon Barang';
?>
<div class="row">
    <div class="small-12 columns">   
        <ul class="button-group">	
            <li><a href="#" class="tiny bigfont button" accesskey="x" id="tombol-autoexpire">Auto E<span class="ak">x</span>pire</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'diskon-barang-grid',
            'dataProvider' => $model->search(),
            'itemsCssClass' => 'tabel-index responsive',
            'filter' => $model,
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'barcode',
                    'header' => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                    'type' => 'raw',
                    'value' => '!is_null($data->barang) ? $data->barang->barcode : ""',
                ),
                array(
                    'class' => 'BDataColumn',
                    'name' => 'namaBarang',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToView'),
                ),
                array(
                    'name' => 'tipe_diskon_id',
                    'filter' => $model->listTipeSort(),
                    'value' => '$data->namaTipeSort'
                ),
                array(
                    'header' => 'Harga Asli',
                    'value' => '!is_null($data->barang) ? $data->barang->hargaJual : ""',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'nominal',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'persen',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                'dari',
                'sampai',
                array(
                    'name' => 'qty',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'qty_min',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'qty_max',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'status',
                    'filter' => $model->listStatus(),
                    'value' => '$data->namaStatus'
                ),
                array(
                    'class' => 'BButtonColumn',
                ),
            ),
        ));
        ?>
    </div>
</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $("#tombol-autoexpire").click(function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        dataUrl = '<?php echo $this->createUrl('autoexpire'); ?>';
        dataKirim = {autoexpire: true};
        console.log(dataKirim);
        $.ajax({
            type: 'POST',
            url: dataUrl,
            data: dataKirim,
            success: function (data) {
                if (data.sukses) {
                    $.gritter.add({
                        title: 'Sukses',
                        text: data.rowAffected + ' diskon dinonaktifkan',
                        time: 3000
                    });
                    $.fn.yiiGridView.update('diskon-barang-grid');
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000
                    });
                }
            }
        });
        return false;
    });

</script>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
