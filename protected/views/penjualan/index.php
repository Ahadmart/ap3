<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Penjualan';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', ['id' => 'penjualan-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'itemsCssClass' => 'tabel-index responsive',
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nomor',
                    'header' => '<span class="ak">N</span>omor',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToView')
                ),
                array(
                    'class' => 'BDataColumn',
                    'name' => 'tanggal',
                    'header' => 'Tangga<span class="ak">l</span>',
                    'accesskey' => 'l',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToUbah')
                ),
                array(
                    'name' => 'namaProfil',
                    'value' => '$data->profil->nama'
                ),
                array(
                    'name' => 'nomorHutangPiutang',
                    'value' => 'isset($data->hutangPiutang) ? $data->hutangPiutang->nomor:""',
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->namaStatus',
                    'filter' => $model->listStatus()
                ),
                array(
                    'header' => 'Total',
                    'value' => '$data->total',
                    'htmlOptions' => array('class' => 'rata-kanan')
                ),
                array(
                    'header' => 'Margin',
                    'value' => '$data->margin',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan')
                ),
                array(
                    'header' => 'Margin (%)',
                    'value' => '$data->profitMargin',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan')
                ),
                array(
                    'name' => 'updated_by',
                    'value' => '$data->updatedBy->nama_lengkap',
                ),
                /* Tombol yang muncul sesuai keadaan
                 * 1. Jika masih draft: maka ada tombol hapus/delete
                 * fixme: di bawah ini belum, insyaAllah menyusul
                 * 2. Jika sudah tidak draft dan belum export csv, maka ada tombol csv
                 * 3. Jika sudah tidak draft dan sudah export csv, maka ada tombol csvsudah
                 */
                [
                    'class' => 'BButtonColumn',
                    'template' => '{csv}{delete}',
                    'buttons' => [
                        'csv' => ['title' => 'Export CSV',
                            'label' => '<i class="fa fa-file-text"></i>',
                            'imageUrl' => false,
                            'url' => 'Yii::app()->controller->createUrl("exportcsv", array("id"=>$data->primaryKey))',
                            'visible' => '$data->status != ' . Penjualan::STATUS_DRAFT,
                        ],
                        'csvsudah' => [
                            'title' => 'Export CSV',
                            'label' => '<i class="fa fa-file-text-o"></i>',
                            'imageUrl' => false,
                            'url' => 'Yii::app()->controller->createUrl("exportcsv", array("id"=>$data->primaryKey))',
                        ],
                        'delete' => [
                            'visible' => '$data->status == ' . Penjualan::STATUS_DRAFT,
                        ]
                    ]
                ],
            ),
        ]);
        ?>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-download"></i> I<span class="ak">m</span>port', 'url' => $this->createUrl('import'), 'linkOptions' => array(
                    'class' => 'warning button',
                    'accesskey' => 'm'
                )),
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-download"></i>', 'url' => $this->createUrl('import'), 'linkOptions' => array(
                    'class' => 'warning button',
                )),
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
