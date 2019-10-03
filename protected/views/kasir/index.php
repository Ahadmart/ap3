<?php
/* @var $this KasirController */
/* @var $model Kasir */

$this->breadcrumbs = [
    'Kasir' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Kasir';
$this->boxHeader['normal'] = 'Kasir';
?>

<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', [
            'id'           => 'kasir-grid',
            'dataProvider' => $model->search(),
            //'filter' => $model,
            'columns'      => [
                //'id',
                //'user_id',
                [
                    'name'  => 'user_id',
                    'value' => '$data->user->nama_lengkap'
                ],
                [
                    'name'  => 'device_id',
                    'value' => '$data->device->keterangan'
                ],
                [
                    'name'  => 'waktu_buka',
                    'value' => 'date_format(date_create_from_format(\'Y-m-d H:i:s\', $data->waktu_buka), \'d-m-Y H:i:s\')',
                ],
                [
                    'name'    => 'waktu_tutup',
                    'value'   => 'isset($data->waktu_tutup) ? date_format(date_create_from_format(\'Y-m-d H:i:s\', $data->waktu_tutup), \'d-m-Y H:i:s\') : ""',
                    'visible' => $showHistory,
                ],
                'saldo_awal',
                /*
                  'saldo_akhir_seharusnya',
                  'saldo_akhir',
                  'total_penjualan',
                  'total_margin',
                  'total_retur',
                  'updated_at',
                  'updated_by',
                  'created_at',
                 */
                [
                    'class'    => 'BButtonColumn',
                    'header'   => 'Tutup / View',
                    'template' => '{tutup} {rekap}',
                    'buttons'  => [
                        'tutup' => [
                            'label'   => '<i class="fa fa-2x fa-sign-out"></i>',
                            'url'     => 'Yii::app()->controller->createUrl("tutup", ["id"=>$data->primaryKey])',
                            'visible' => 'empty($data->waktu_tutup)',
                            'options' => [
                                'title' => 'Tutup Kasir'
                            ]
                        ],
                        'rekap' => [
                            'label'   => '<i class="fa fa-file-text"></i>
                                          </span>',
                            'url'     => 'Yii::app()->controller->createUrl("rekap", ["id"=>$data->primaryKey])',
                            'visible' => '!empty($data->waktu_tutup)',
                            'options' => [
                                'title' => 'Rekap'
                            ]
                        ],
                    ]
                ],
            ],
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <ul class="button-group">
            <li>
                <a href="#" accesskey="c" data-dropdown="cdkick" aria-controls="cdkick" aria-expanded="false" class="tiny bigfont success button dropdown"><i class="fa fa-microchip fa-fw"></i> Buka <span class="ak">C</span>ash Drawer</a>
                <ul id="cdkick" data-dropdown-content class="small f-dropdown content" aria-hidden="true">
                    <?php
                    foreach ($printerLpr as $printer) {
                        ?>                      
                        <li>
                            <a class="tombol-cdkick" href="<?=
                               $this->createUrl('opencashdrawer', [
                                   'id' => $printer['id'],
                               ])
                               ?>">
                                <?= $printer['nama']; ?> <small><?= $printer['keterangan']; ?></small></a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </li>
        </ul>      
    </div>
</div>
<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery.gritter.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.gritter.min.js', CClientScript::POS_HEAD);
?>
<script>
    $(".tombol-cdkick").click(function () {
        dataUrl = $(this).attr('href');
        console.log(dataUrl);

        $.ajax({
            type: 'POST',
            url: dataUrl,
            success: function (data) {
                if (data.sukses) {
                    //Sukses
                } else {
                    $.gritter.add({
                        title: 'Error ' + data.error.code,
                        text: data.error.msg,
                        time: 3000,
                    });
                }
            }
        });
        return false;
    });
</script>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => ''],
    ['itemOptions'    => ['class' => 'has-form hide-for-small-only'], 'label'          => '',
        'items'          => [
            ['label'       => '<i class="fa fa-plus"></i> Buka (<span class="ak">t</span>)', 'url'         => $this->createUrl('buka'), 'linkOptions' => [
                    'class'     => 'button',
                    'accesskey' => 't'
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions'    => ['class' => 'has-form show-for-small-only'], 'label'          => '',
        'items'          => [
            ['label'       => '<i class="fa fa-plus"></i>', 'url'         => $this->createUrl('buka'), 'linkOptions' => [
                    'class' => 'button',
                ]],
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
