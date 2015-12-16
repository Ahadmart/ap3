<?php
/* @var $this KasirController */
/* @var $model Kasir */

$this->breadcrumbs = array(
    'Kasir' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Kasir';
$this->boxHeader['normal'] = 'Kasir';
?>

<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'kasir-grid',
            'dataProvider' => $model->search(),
            //'filter' => $model,
            'columns' => array(
                //'id',
                //'user_id',
                array(
                    'name' => 'user_id',
                    'value' => '$data->user->nama_lengkap'
                ),
                array(
                    'name' => 'device_id',
                    'value' => '$data->device->keterangan'
                ),
                'waktu_buka',
                //'waktu_tutup',
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
                array(
                    'class' => 'BButtonColumn',
                    'header' => 'Tutup',
                    'template' => '{tutup}',
                    'buttons' => array(
                        'tutup' => array(
                            'label' => '<i class="fa fa-2x fa-sign-out"></i>',
                            'url' => 'Yii::app()->controller->createUrl("tutup", array("id"=>$data->primaryKey))',
                            'options' => array(
                                'title' => 'Tutup Kasir'
                            )
                        )
                    )
                ),
            ),
        ));
        ?>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> Buka (<span class="ak">t</span>)', 'url' => $this->createUrl('buka'), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('buka'), 'linkOptions' => array(
                    'class' => 'button',
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
