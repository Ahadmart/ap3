<?php
/* @var $this ItemController */

$this->breadcrumbs = array(
    'Item Otorisasi',
);

$this->boxHeader['small'] = 'Item';
$this->boxHeader['normal'] = 'Item Otorisasi';
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'auth-item-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'name',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => array($this, 'renderLinkToUbah'),
                ),
                array(
                    'name' => 'type',
                    'value' => '$data->authTypeName',
                    'filter' => array('0' => 'Operation', '1' => 'Task', '2' => 'Role')
                ),
                'description',
                'bizrule',
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
