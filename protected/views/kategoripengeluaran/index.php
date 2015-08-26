<?php
/* @var $this KategoripengeluaranController */
/* @var $model KategoriPengeluaran */

$this->breadcrumbs = array(
    'Kategori Pengeluaran' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Kategori Pengeluaran';
$this->boxHeader['normal'] = 'Kategori Pengeluaran';
?>
<div class="row">
   <div class="small-12 columns">
      <?php
      $this->widget('BGridView', array(
          'id' => 'kategori-pengeluaran-grid',
          'dataProvider' => $model->search(),
          'filter' => $model,
          'columns' => array(
              array(
                  'class' => 'BDataColumn',
                  'name' => 'nama',
                  'header' => '<span class="ak">N</span>ama',
                  'accesskey' => 'n',
                  'type' => 'raw',
                  'value' => array($this, 'renderLinkToView')
              ),
              'deskripsi',
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
                    'class' => 'button'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
