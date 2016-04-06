<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>

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
                    'name' => 'Qty Tercatat',
                    'value' => '$data->stok',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false
                ),
                array(
                    'header' => '<span class="ak">Q</span>ty Asli',
                    'type' => 'raw',
                    'value' => array($this, 'renderQtyLinkEditable'),
                    'headerHtmlOptions' => array('style' => 'width:75px;', 'class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
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
<script>
    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            params: {'soId': '<?php echo $model->id; ?>'},
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("barang-grid");
                    $.fn.yiiGridView.update("so-detail-grid");
                    //updateTotal();
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