<?php
/* @var $this ProfilController */
/* @var $model Profil */

$this->breadcrumbs = array(
    'Profil' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Profil';
$this->boxHeader['normal'] = 'Profil';
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'profil-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'tipe_id',
                    'value' => '$data->namaTipe',
//                    'filter' => array(
//                        Profil::TIPE_SUPPLIER => 'Supplier',
//                        Profil::TIPE_CUSTOMER => 'Customer',
//                        Profil::TIPE_KARYAWAN => 'Karyawan'
//                    )
                    'filter' => $model->listTipe(),
                ),
                'nomor',
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToView'),
                ),
                'alamat1',
                'alamat2',
                'alamat3',
                'telp',
                'hp',
                'surel',
                'keterangan',
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
