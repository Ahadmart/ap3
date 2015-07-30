<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$label=$this->class2name($this->modelClass);
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Tambah',
);\n";
?>

$this->boxHeader['small'] = 'Tambah';
$this->boxHeader['normal'] = 'Tambah <?php echo $label; ?>';
?>
<div class="row">
    <div class="large-6 columns">
        <?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>\n"; ?>
    </div>
</div>
<?php echo "<?php\n"; ?>

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