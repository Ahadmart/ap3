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
        <?php
        $this->widget('BGridView', array(
            'id' => 'diskon-barang-grid',
            'dataProvider' => $model->search(),
            'itemsCssClass' => 'tabel-index responsive',
            'filter' => $model,
            'columns' => array(
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
                'nominal',
                'persen',
                'dari',
                'sampai',
                'qty',
                'qty_min',
                'qty_max',
                array(
                    'name' => 'status',
                    'filter' => $model->listStatus(),
                    'value' => '$data->namaStatus'
                ),
                /*
                  'updated_at',
                  'updated_by',
                  'created_at',
                 */
                array(
                    'class' => 'BButtonColumn',
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
