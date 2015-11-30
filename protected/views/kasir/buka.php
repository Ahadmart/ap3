<?php

/* @var $this KasirController */
/* @var $model Kasir */

$this->breadcrumbs = array(
    'Kasir' => array('index'),
    'Tambah',
);

$this->boxHeader['small'] = 'Buka';
$this->boxHeader['normal'] = 'Buka Kasir';
?>

<?php

$this->renderPartial('_form_buka', array(
    'model' => $model,
    'listKasir' => $listKasir,
    'listPosClient' => $listPosClient
));
?>

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
                )),
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);
