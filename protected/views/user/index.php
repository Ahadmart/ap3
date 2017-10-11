<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs = array(
    'User' => array('index'),
    'Index',
);

$this->boxHeader['small'] = 'User';
$this->boxHeader['normal'] = 'User';
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'user-grid',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    'value' => function($data) {
                        return '<a href="' . Yii::app()->controller->createUrl('view', array('id' => $data->id)) . '">' . $data->nama . '</a>';
                    }
                ),
                'nama_lengkap',
                [
                    'name' => 'theme_id',
                    'value' => '$data->namaTheme',
                    'filter'=> Theme::model()->listTheme()
                ],
                [
                    'name' => 'menu_id',
                    'value' => '$data->namaMenu',
                    'filter'=> Menu::model()->listMenuRootSimple()
                ],
                array(
                    'class' => 'BButtonColumn',
                ),
                [
                    'class' => 'BDataColumn',
                    'header' => 'Role',
                    'value' => [$this, 'renderLinkToAssignment'],
                    'type' => 'raw',
                ],
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
                    'accesskey' => 't'
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
