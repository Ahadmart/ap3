<?php
/* @var $this SalesorderController */
/* @var $model So */

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js',
        CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js',
        CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');

$this->breadcrumbs = [
    'Sales Order' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Sales Order';
$this->boxHeader['normal'] = 'Sales Order (Pesanan Penjualan)';
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView',
                [
            'id'           => 'so-grid',
            'dataProvider' => $model->search(),
            'filter'       => $model,
            'columns'      => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nomor',
                    'header'    => '<span class="ak">N</span>omor',
                    'accesskey' => 'n',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkNomor']
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'tanggal',
                    'header'    => 'Tangga<span class="ak">l</span>',
                    'accesskey' => 'l',
                    'type'      => 'raw',
                    'value'     => [$this, 'renderLinkTanggalToUbah']
                ],
                [
                    'name'  => 'namaProfil',
                    'value' => '$data->profil->nama'
                ],
                [
                    'name'   => 'status',
                    'type'   => 'raw',
                    'value'  => '$data->namaStatus',
                    'filter' => $model->listStatus(),
                    'value'  => [$this, 'renderEditableStatus']
                ],
                [
                    'header'      => 'Total',
                    'value'       => '$data->total',
                    'htmlOptions' => ['class' => 'rata-kanan']
                ],
                [
                    'name'  => 'namaUser',
                    'value' => '$data->updatedBy->nama_lengkap',
                ],
                [
                    'class'           => 'BButtonColumn',
                    'deleteButtonUrl' => 'Yii::app()->controller->createUrl("batal", array("id"=>$data->primaryKey))',
                    'buttons'         => [
                        'delete' => [
                            'visible' => '$data->status == ' . So::STATUS_DRAFT . ' OR $data->status == ' . So::STATUS_PESAN,
                        ]
                    ]
                ],
            ],
        ]);
        ?>
    </div>
</div>
<script>

    function enableEditable() {
        $(".editable-status").editable({
            mode: "inline",
            //inputclass: "input-editable-qty",
            success: function (response, newValue) {
                if (response.sukses) {
                    $.fn.yiiGridView.update("so-grid");
                }
            },
            source: [
                {value: <?= So::STATUS_PESAN ?>, text: 'Pesan'}
            ]
        }
        );
    }

    $(function () {
        enableEditable();
    });

    $(document).ajaxComplete(function () {
        enableEditable();
    });
</script>
<?php
$this->menu                = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    ['itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label'          => '',
        'items'          => [
            ['label'       => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url'         => $this->createUrl('tambah'), 'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 't'
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label'          => '',
        'items'          => [
            ['label'       => '<i class="fa fa-plus"></i>', 'url'         => $this->createUrl('tambah'), 'linkOptions' => [
                    'class' => 'button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
