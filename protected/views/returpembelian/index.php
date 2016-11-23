<?php
/* @var $this ReturpembelianController */
/* @var $model ReturPembelian */

$this->breadcrumbs = array(
    'Retur Pembelian' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Retur Pembelian';
$this->boxHeader['normal'] = 'Retur Pembelian';

?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'retur-pembelian-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'itemsCssClass' => 'tabel-index',
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
                    'class' => 'BDataColumn',
                    'name' => 'namaSupplier',
                    'header' => '<span class="ak">S</span>upplier',
                    'accesskey' => 's',
                    'type' => 'raw',
                    'value' => '$data->profil->nama'
                // 'value' => array($this, 'renderLinkToSupplier')
                ),
//		  'nomor',
//		  'tanggal',
//		  'profil_id',
//		  'status',
                array(
                    'name' => 'status',
                    'value' => '$data->namaStatus',
                    'filter' => array(
                        ReturPembelian::STATUS_DRAFT => 'Draft',
                        ReturPembelian::STATUS_PIUTANG => 'Piutang',
                        ReturPembelian::STATUS_LUNAS => 'Lunas'
                    )
                ),
                array(
                    'header' => 'Total',
                    'value' => '$data->total',
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                    'htmlOptions' => array('class' => 'rata-kanan')
                ),
                array(
                    'name' => 'namaUpdatedBy',
                    'value' => '$data->updatedBy->nama_lengkap',
                ),
                array(
                    'class' => 'BButtonColumn',
                    'buttons' => [
                        'delete' => [
                            'visible' => '$data->status == ' . ReturPembelian::STATUS_DRAFT,
                        ]
                    ]
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
