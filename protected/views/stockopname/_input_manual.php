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
            'parentModelId' => $model->id,
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
                    'header' => 'Qty Tercatat',
                    'value' => '$data->stok',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'filter' => false
                ),
                // array(
                //     'header' => 'Draft Retur Beli',
                //     'value' => '$data->qtyReturBeli',
                //     'htmlOptions' => array('class' => 'rata-kanan'),
                //     'headerHtmlOptions' => array('class' => 'rata-kanan'),
                //     'filter' => false,
                //     'visible' => $showQtyReturBeli
                // ),
                array(
                    'header' => '<span class="ak">Q</span>ty Asli',
                    'type' => 'raw',
                    'value' => array($this, 'renderQtyLinkEditable'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan'),
                ),
                array(
                    //'header' => 'Set 0 (nol)',
                    'type' => 'raw',
                    'value' => array($this, 'renderTombolSetNol'),
                    'headerHtmlOptions' => array('class' => 'rata-tengah'),
                    'htmlOptions' => array('class' => 'rata-tengah'),
                    'header' => 'Set 0 (nol)<br /><a id="tombol-setnol-all" href="#" class="delete">All <i class="fa fa-check-square"><i></a>'
                ),
                array(
                    // 'header' => 'Set inaktif',
                    'type' => 'raw',
                    'value' => array($this, 'renderTombolSetInAktif'),
                    'headerHtmlOptions' => array('class' => 'rata-tengah'),
                    'htmlOptions' => array('class' => 'rata-tengah'),
                    'header' => 'Set inaktif<br /><a id="tombol-inaktif-all" href="#" class="delete">All <i class="fa fa-check-square"><i></a>'
                ),
                array(
                    'header' => 'Pindah Rak',
                    'type' => 'raw',
                    'value' => array($this, 'renderGantiRakLinkEditable'),
                    'headerHtmlOptions' => array('class' => 'rata-tengah'),
                    'htmlOptions' => array('class' => 'rata-tengah'),
                ),
            )
        ));
        ?>
    </div>
</div>
<hr />
<script>
    $(function() {
        $(document).on('click', ".tombol-setnol", function() {
            dataUrl = '<?php echo $this->createUrl('setnol', array('id' => $model->id)); ?>';
            dataKirim = {
                barangid: $(this).data('barangid')
            };
            console.log(dataUrl);

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function(data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update("barang-grid");
                        $.fn.yiiGridView.update("so-detail-grid");
                    }
                }
            });
            return false;
        });
        $(document).on('click', ".tombol-setinaktif", function() {
            dataUrl = '<?php echo $this->createUrl('setinaktif', array('id' => $model->id)); ?>';
            dataKirim = {
                barangid: $(this).data('barangid')
            };
            console.log(dataUrl);

            $.ajax({
                type: 'POST',
                url: dataUrl,
                data: dataKirim,
                success: function(data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update("barang-grid");
                    }
                }
            });
            return false;
        });
        $(document).on('click', "#tombol-setnol-all", function() {
            $("#barang-grid").addClass("grid-loading");
            dataUrl = '<?php echo $this->createUrl('setnolall', array('id' => $model->id)); ?>';
            $.ajax({
                //type: 'POST',
                url: dataUrl,
                success: function(data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update("barang-grid");
                        $.fn.yiiGridView.update("so-detail-grid");
                    } else {
                        alert('Ada Error!')
                    }
                }
            });
            return false;
        });
        $(document).on('click', "#tombol-inaktif-all", function() {
            $("#barang-grid").addClass("grid-loading");
            dataUrl = '<?php echo $this->createUrl('setinaktifall', array('id' => $model->id)); ?>';
            $.ajax({
                //type: 'POST',
                url: dataUrl,
                success: function(data) {
                    if (data.sukses) {
                        $.fn.yiiGridView.update("barang-grid");
                        $.fn.yiiGridView.update("so-detail-grid");
                    } else {
                        alert('Ada Error!')
                    }
                }
            });
            return false;
        });
    });


    function enableEditable() {
        $(".editable-qty").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            params: {
                'soId': '<?php echo $model->id; ?>'
            },
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("barang-grid");
                    $.fn.yiiGridView.update("so-detail-grid");
                }
            }
        });
        $('.editable-rak').editable({
            mode: "inline",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("barang-grid");
                    $.fn.yiiGridView.update("so-detail-grid");
                }
            },
            source: [
                <?php
                $listRak = CHtml::listData(RakBarang::model()->findAll(array('select' => 'id,nama', 'order' => 'nama')), 'id', 'nama');
                $firstRow = TRUE;
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