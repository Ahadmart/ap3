<?php
/* @var $this DeviceController */
/* @var $model Device */

$this->breadcrumbs = array(
    'Device' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Device';
$this->boxHeader['normal'] = 'Device';
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'device-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'tipe_id',
                    'value' => '$data->namaTipe',
                    'filter' => $model->listTipe()
                ),
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToView'),
                ),
                array(
                    'name' => 'keterangan',
                    'filter' => false
                ),
                array(
                    'name' => 'address',
                    'filter' => false
                ),
                array(
                    'name' => 'default_printer_id',
                    'value' => 'isset($data->defaultPrinter) ? $data->defaultPrinter->nama : ""',
                    'filter' => false
                ),
                array(
                    'name' => 'lf_sebelum',
                    'filter' => false,
                ),
                array(
                    'name' => 'lf_setelah',
                    'filter' => false,
                ),
                array(
                    'name' => 'paper_autocut',
                    'filter' => false,
                ),
                array(
                    'name' => 'cashdrawer_kick',
                    'filter' => false,
                ),
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
