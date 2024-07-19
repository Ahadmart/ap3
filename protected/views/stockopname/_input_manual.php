<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>

<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'            => 'barang-grid',
            'dataProvider'  => $barangBelumSO->search(),
            'filter'        => $barangBelumSO,
            'parentModelId' => $model->id,
            'itemsCssClass' => 'tabel-index responsive',
            'columns'       => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'barcode',
                    'header'    => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nama',
                    'header'    => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type'      => 'raw',
                ],
                [
                    'name'              => 'Stok',
                    'header'            => 'Qty Tercatat',
                    'value'             => '$data->stok',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'filter'            => false,
                ],
                [
                    'header'            => 'Qty Retur Beli',
                    'value'             => '$data->qtyReturBeliPosted',
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'filter'            => false,
                ],
                [
                    'header' => 'Input',
                    'type' => 'raw',
                    'value' => [$this, 'renderFormInputManual']
                ]
                /*
                [
                    'header'            => '<span class="ak">Q</span>ty Asli',
                    'type'              => 'raw',
                    'value'             => [$this, 'renderQtyLinkEditable'],
                    'headerHtmlOptions' => ['class' => 'rata-kanan'],
                    'htmlOptions'       => ['class' => 'rata-kanan'],
                ],
                [
                    //'header' => 'Set 0 (nol)',
                    'type'              => 'raw',
                    'value'             => [$this, 'renderTombolSetNol'],
                    'headerHtmlOptions' => ['class' => 'rata-tengah'],
                    'htmlOptions'       => ['class' => 'rata-tengah'],
                    'header'            => 'Set 0 (nol)<br /><a id="tombol-setnol-all" href="#" class="delete">All <i class="fa fa-check-square"><i></a>',
                ],
                [
                    // 'header' => 'Set inaktif',
                    'type'              => 'raw',
                    'value'             => [$this, 'renderTombolSetInAktif'],
                    'headerHtmlOptions' => ['class' => 'rata-tengah'],
                    'htmlOptions'       => ['class' => 'rata-tengah'],
                    'header'            => 'Set inaktif<br /><a id="tombol-inaktif-all" href="#" class="delete">All <i class="fa fa-check-square"><i></a>',
                ],
                [
                    'header'            => 'Pindah Rak',
                    'type'              => 'raw',
                    'value'             => [$this, 'renderGantiRakLinkEditable'],
                    'headerHtmlOptions' => ['class' => 'rata-tengah'],
                    'htmlOptions'       => ['class' => 'rata-tengah'],
                ],
                */
            ],
        ]);
        ?>
    </div>
</div>
<hr />
<script>
    $(function() {
        $(document).on('click', ".tombol-setnol", function() {
            dataUrl = '<?php echo $this->createUrl('setnol', ['id' => $model->id]); ?>';
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
            dataUrl = '<?php echo $this->createUrl('setinaktif', ['id' => $model->id]); ?>';
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
            dataUrl = '<?php echo $this->createUrl('setnolall', ['id' => $model->id]); ?>';
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
            dataUrl = '<?php echo $this->createUrl('setinaktifall', ['id' => $model->id]); ?>';
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

    $(".forminputmanual").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            data: $(this).serialize(),
            type: 'POST',
            success: function(r) {
                if (r.sukses) {
                    $("#barang-grid").yiiGridView('update');
                    $("#so-detail-grid").yiiGridView('update');
                } else {
                    $.gritter.add({
                        title: 'Error ' + r.error.code,
                        text: r.error.msg,
                        time: 5000,
                    })
                }
            },
            error: function(xhr, status, error) {
                console.log('An error occurred: ' + error);
            }
        })
    })
</script>