<?php
/* @var $this MemberController */
/* @var $model MemberPeriodePoin */

$this->breadcrumbs = array(
    'Member Periode Poin' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'Member';
$this->boxHeader['normal'] = 'Konfigurasi Member Poin';

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/responsive-tables.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/responsive-tables.js', CClientScript::POS_HEAD);
?>
<div class="row">
    <div class="small-12 medium-6 column">
        <div class="panel">
            <?php $this->renderPartial('_config', array('modelConfig' => $modelConfig)); ?>
        </div>
    </div>
    <div class="small-12 medium-6 column">
        <div class="panel">
            <h4>Periode Poin</h4>
            <hr />
            <?php $this->renderPartial('_form', array('model' => $model)); ?>
            <?php
            $this->widget('BGridView', array(
                'id' => 'member-periode-poin-grid',
                'itemsCssClass' => 'tabel-index responsive',
                'dataProvider' => $modelIndex->search(),
                //'filter' => $modelIndex,
                'columns' => array(
                    'nama',
                    array(
                        'name' => 'awal',
                        'value' => '$data->getNamaBulan($data->awal)'
                    ),
                    array(
                        'name' => 'akhir',
                        'value' => '$data->getNamaBulan($data->akhir)'
                    ),
                    array(
                        'class' => 'BButtonColumn',
                    ),
                ),
            ));
            ?>
        </div>
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
