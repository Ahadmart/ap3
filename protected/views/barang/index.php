<?php
/* @var $this BarangController */
/* @var $model Barang */

$this->breadcrumbs = array(
    'Barang' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Barang';
$this->boxHeader['normal'] = 'Barang';

//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
//Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);

// Agar focus tetap di input cari barcode setelah pencarian
Yii::app()->clientScript->registerScript('editableQty', ''
    . '$( document ).ajaxComplete(function() {'
    . '$("input[name=\'Barang[barcode]\'").select();'
    . '});');
?>
<div class="row" style="overflow: auto">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'barang-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'itemsCssClass' => 'tabel-index',
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'barcode',
                    'header' => '<span class="ak">B</span>arcode',
                    'accesskey' => 'b',
                    'autoFocus' => true
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
                    'name' => 'satuan_id',
                    'value' => '$data->satuan->nama',
                    'filter' => $model->filterSatuan()
                ),
                array(
                    'name' => 'kategori_id',
                    'value' => '$data->kategori->nama',
                    'filter' => $model->filterKategori()
                ),
                [
                    'name'  => 'strukturFullPath',
                    'value' => '$data->namaStruktur',
                ],
                array(
                    'name' => 'rak_id',
                    'value' => '$data->rak == null ? "NULL":$data->rak->nama',
                    'filter' => $model->filterRak()
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->namaStatus',
                    'filter' => $model->filterStatus()
                ),
                array(
                    'header' => 'Harga Jual',
                    'value' => '$data->hargaJual',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('class' => 'rata-kanan'),
                ),
                array(
                    'name' => 'Stok',
                    'value' => '$data->stok',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false
                ),
                array(
                    'name' => 'Qty Retur Beli',
                    'value' => '$data->qtyReturBeli',
                    'htmlOptions' => array('class' => 'rata-kanan'),
                    'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                    'filter' => false,
                    'visible' => $showQtyReturBeli
                ),
                /*
                  array(
                  'name' => 'restock_point',
                  'htmlOptions' => array('class' => 'rata-kanan'),
                  'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                  'filter' => false,
                  'sortable' => false
                  ),
                  array(
                  'name' => 'restock_level',
                  'htmlOptions' => array('class' => 'rata-kanan'),
                  'headerHtmlOptions' => array('style' => 'width:75px', 'class' => 'rata-kanan'),
                  'filter' => false,
                  'sortable' => false
                  ),
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
    array(
        'itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i> <span class="ak">T</span>ambah', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                'class' => 'button',
                'accesskey' => 't'
            )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array(
        'itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => '',
        'items' => array(
            array('label' => '<i class="fa fa-plus"></i>', 'url' => $this->createUrl('tambah'), 'linkOptions' => array(
                'class' => 'button',
            )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
