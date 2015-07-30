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
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->class2name($this->modelClass);
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->boxHeader['small'] = 'View';
$this->boxHeader['normal'] = '<?php echo $label; ?>: '.$model->nama;
?>
<div class="row">
    <div class="small-12 columns">
<?php echo "<?php"; ?> $this->widget('BDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>
    </div>
</div>
<?php echo "<?php\n"; ?>
$this->menu = array(
    array('itemOptions' => array('class' => 'divider'), 'label' => false),
    array('itemOptions' => array('class' => 'has-form hide-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-pencil"></i> <span class="ak">U</span>bah', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'button',
                    'accesskey' => 'u'
                )),
            array('label' => '<i class="fa fa-times"></i> <span class="ak">H</span>apus', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'accesskey' => 'h',
                    'submit'=>array('hapus','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),
                    'confirm'=>'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                    'accesskey' => 'i'
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    ),
    array('itemOptions' => array('class' => 'has-form show-for-small-only'), 'label' => false,
        'items' => array(
            array('label' => '<i class="fa fa-pencil"></i>', 'url' => $this->createUrl('ubah', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'button',
                )),
            array('label' => '<i class="fa fa-times"></i>', 'url' => $this->createUrl('hapus', array('id' => $model->id)), 'linkOptions' => array(
                    'class' => 'alert button',
                    'submit'=>array('hapus','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),
                    'confirm'=>'Anda yakin?'
                )),
            array('label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('index'), 'linkOptions' => array(
                    'class' => 'success button',
                ))
        ),
        'submenuOptions' => array('class' => 'button-group')
    )
);