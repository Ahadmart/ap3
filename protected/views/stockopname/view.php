<?php
/* @var $this StockopnameController */
/* @var $model StockOpname */

$this->breadcrumbs = array(
    'Stock Opname' => array('index'),
    $model->id,
);

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = 'Stock Opname: '.$model->nomor;
?>
<div class="row">
   <div class="small-12 columns header">
      <span class="secondary label">Tanggal</span><span class="label"><?php echo $model->tanggal; ?></span>
      <span class="secondary label">Rak</span><span class="label"><?php echo $model->rak->nama; ?></span>
      <span class="secondary label">Status</span><span class="warning label"><?php echo $model->getNamaStatus(); ?></span>
   </div>
</div>
<div class="row">
   <div class="small-12  columns">
      <?php
      $this->widget('BGridView', array(
          'id' => 'so-detail-grid',
          'dataProvider' => $detail->search(),
          'filter' => $detail,
          //'summaryText' => '{start}-{end} dari {count}, Total: ' . $model->total,
          'columns' => array(
              array(
                  'class' => 'BDataColumn',
                  'name' => 'barcode',
                  'header' => '<span class="ak">B</span>arcode',
                  'accesskey' => 'b',
                  'value' => '$data->barang->barcode',
              ),
              array(
                  'class' => 'BDataColumn',
                  'name' => 'namaBarang',
                  'value' => '$data->barang->nama',
                  'header' => '<span class="ak">N</span>ama Barang',
                  'accesskey' => 'n',
              ),
              array(
                  'name' => 'qty_tercatat',
                  'filter' => false,
                  'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                  'htmlOptions' => array('class' => 'rata-kanan'),
              ),
              array(
                  'name' => 'qty_sebenarnya',
                  'filter' => false,
                  'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                  'htmlOptions' => array('class' => 'rata-kanan'),
              ),
              array(
                  'header' => 'Selisih',
                  'value' => '$data->selisih',
                  'filter' => false,
                  'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                  'htmlOptions' => array('class' => 'rata-kanan'),
              )
          ),
      ));
      ?>
   </div>
</div>
<?php
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
