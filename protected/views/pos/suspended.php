<?php
/* @var $this PenjualanController */
/* @var $model Penjualan */

$this->breadcrumbs = array(
    'Penjualan' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Suspended';
$this->boxHeader['normal'] = '<i class="fa fa-shopping-cart fa-lg"></i> Suspended';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<script>
    $(function () {
        $("#tombol-new").focus();
    });
</script>
<div class="medium-10 columns" style="/*height: 100%; overflow: scroll*/">
    <?php
    $this->widget('BGridView', array(
        'id' => 'penjualan-grid',
        'dataProvider' => $model->search(),
        'filter' => $model,
        'itemsCssClass' => 'tabel-index responsive',
        'template' => '{items}{summary}{pager}',
        'columns' => array(
            array(
                'class' => 'BDataColumn',
                'name' => 'tanggal',
                'header' => 'Tang<span class="ak">g</span>al',
                'accesskey' => 'g',
                'type' => 'raw',
                'value' => array($this, 'renderLinkToUbah')
            ),
            array(
                'name' => 'namaProfil',
                'value' => '$data->profil->nama'
            ),
            array(
                'header' => 'Total',
                'value' => '$data->total',
                'htmlOptions' => array('class' => 'rata-kanan'),
                'headerHtmlOptions' => array('class' => 'rata-kanan')
            ),
        /*
          array(
          'class' => 'BButtonColumn',
          ),
         */
        ),
    ));
    ?>
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
