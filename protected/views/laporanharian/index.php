<?php
/* @var $this LaporanharianController */
/* @var $model LaporanHarian */

$this->breadcrumbs = array(
    'Laporan Harian' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Laporan Harian';
$this->boxHeader['normal'] = 'Laporan Harian';
?>

<div class="row">
   <div class="small-12 columns">
      <?php $this->renderPartial('_form', array('model' => $model)); ?>
   </div>
</div>
<?php



//$this->widget('BGridView', array(
//	'id'=>'laporan-harian-grid',
//	'dataProvider'=>$model->search(),
//	'filter'=>$model,
//	'columns'=>array(
//		'id',
//		'tanggal',
//		'saldo_akhir',
//		'keterangan',
//		'updated_at',
//		'updated_by',
//		/*
//		'created_at',
//		*/
//		array(
//			'class'=>'BButtonColumn',
//		),
//	),
//));

//$this->menu = array(
//    array('itemOptions' => array('class' => 'divider'), 'label' => ''),
//    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
//        'items' => array(
//            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
//                    'class' => 'button',
//                    'accesskey' => 't'
//                )),
//        ),
//        'submenuOptions' => array('class' => 'button-group')
//    ),
//    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
//        'items' => array(
//            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
//                    'class' => 'button',
//                )),
//        ),
//        'submenuOptions' => array('class' => 'button-group')
//    )
//);