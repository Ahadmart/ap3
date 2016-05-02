<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Penjualan';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Penjualan';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
   <div class="small-12 columns">
      <?php
      $this->widget('BGridView', array(
          'id' => 'penjualan-grid',
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
