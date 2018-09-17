<?php
/* @var $this PosController */
/* @var $model PesananPenjualan */

//$this->boxHeader['small'] = 'Suspended';
//$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Suspended';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js',
        CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        //$("#tombol-new").focus();
    });
</script>
<div class="medium-10 columns" style="/*height: 100%; overflow: scroll*/">
    <?php
    $this->widget('BGridView',
            [
        'id'            => 'pesanan-grid',
        'dataProvider'  => $model->search(),
        'filter'        => $model,
        'itemsCssClass' => 'tabel-index responsive',
        'template'      => '{items}{summary}{pager}',
        'columns'       => [
            [
                'class'     => 'BDataColumn',
                'name'      => 'nomorTanggal',
                'header'    => 'Nomor Tang<span class="ak">g</span>al',
                'accesskey' => 'g',
                'type'      => 'raw',
                'value'     => [$this, 'renderPesananColumn']
            ],
            [
                'name'  => 'namaProfil',
                'value' => '$data->profil->nama'
            ],
            [
                'header'            => 'Total',
                'value'             => '$data->total',
                'htmlOptions'       => ['class' => 'rata-kanan'],
                'headerHtmlOptions' => ['class' => 'rata-kanan']
            ],
            [
                'class'           => 'BButtonColumn',
                'deleteButtonUrl' => 'Yii::app()->controller->createUrl("pesananbatal", array("id"=>$data->primaryKey))',
                'buttons'         => [
                    'delete' => [
                        'visible' => '$data->status == ' . PesananPenjualan::STATUS_DRAFT . ' OR $data->status == ' . PesananPenjualan::STATUS_PESAN,
                    ]
                ]
            ],
        ],
    ]);
    ?>
</div>