<?php
/* @var $this PpnpembelianController */
/* @var $model PembelianPpn */

$this->breadcrumbs=array(
	'Pembelian Ppn'=>array('index'),
	$model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Pembelian Ppn: '.$model->nama;
?>
<div class="row">
    <div class="small-12 columns">
<?php $this->widget('BDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'pembelian_id',
		'no_faktur_pajak',
		'total_ppn_hitung',
		'total_ppn_faktur',
		'status',
		'updated_at',
		'updated_by',
		'created_at',
	),
)); ?>
    </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 'u'
                )),
            array('label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'accesskey' => 'h',
                    'submit'=>array('hapus','id'=>$model->id),
                    'confirm'=>'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'submit'=>array('hapus','id'=>$model->id),
                    'confirm'=>'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);