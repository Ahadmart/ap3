<?php
/* @var $this PembelianController */
/* @var $model Pembelian */

$this->breadcrumbs = array(
    'Pembelian' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Pembelian';
$this->boxHeader['normal'] = 'Pembelian';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
   <div class="small-12 columns">
      <?php
      $this->widget('BGridView', array(
          'id' => 'pembelian-grid',
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
                  'class' => 'BDataColumn',
                  'name' => 'namaSupplier',
                  'header' => 'Pro<span class="ak">f</span>il',
                  'accesskey' => 'f',
                  'type' => 'raw',
                  'value' => '$data->profil->nama'
              // 'value' => array($this, 'renderLinkToSupplier')
              ),
              'referensi',
              'tanggal_referensi',
              array(
                  'name' => 'nomorHutang',
                  'value' => 'is_null($data->hutangPiutang)?"":$data->hutangPiutang->nomor'
              ),
              array(
                  'name' => 'status',
                  'value' => '$data->namaStatus',
                  'filter' => array('0' => 'Draft', '1' => 'Hutang', '2' => 'Lunas')
              ),
              array(
                  'header' => 'Total',
                  'value' => '$data->total',
                  'headerHtmlOptions' => array('class' => 'rata-kanan'),
                  'htmlOptions' => array('class' => 'rata-kanan')
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
