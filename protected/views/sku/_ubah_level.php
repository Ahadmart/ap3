<?php
/* @var $this SkuController */
/* @var $modelLevel SkuLevel */

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');

$this->widget('BGridView', [
    'id'           => 'sku-level-grid',
    'dataProvider' => $modelLevel->search(),
    // 'filter' => $modelLevel,
    'columns'      => [
        'level',
        [
            'name' => 'namaSatuan',
            'type' => 'raw',
            'value' => [$this, 'renderNamaSatuan']
        ],
        [

            'name'  => 'rasio_konversi',
            'type'  => 'raw',
            'value' => [$this, 'renderRasioKonversi'],
        ],
        'jumlah_per_unit',
        [
            'class'           => 'BButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("hapuslevel", ["id"=>$data->primaryKey])',
            'buttons'         => [
                'delete' => [
                    'visible' => '$data->level == ' . $levelMax,
                ],
            ],
        ],
        /*
    'updated_at',
    'updated_by',
    'created_at',
     */
    ],
]);
?>

<script>
    function enableEditable() {
        $(".editable-rasio").editable({
            mode: "inline",
            inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("sku-level-grid");
                }
            }
        });
        $(".editable-satuan").editable({
            mode: "inline",
            //inputclass: "input-editable-qty",
            success: function(response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("sku-level-grid");
                    $.fn.yiiGridView.update("sku-detail-grid");
                }
            },
            source: [
                <?php
                $listSatuan  = CHtml::listData(SatuanBarang::model()->findAll(['select' => 'id,nama', 'order' => 'nama']), 'id', 'nama');
                $firstRow = true;
                foreach ($listSatuan as $key => $value) :
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