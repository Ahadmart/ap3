<?php
/* @var $this MembershipconfigController */
/* @var $model MembershipConfig */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery.poshytip.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/vendor/jquery-editable-poshytip.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/jquery-editable.css');
/*
 * 	Menambahkan rutin pada saat edit value
 * 1. Update Grid Config
 */
Yii::app()->clientScript->registerScript('editableQty', ''
    . '$( document ).ajaxComplete(function() {'
    . '$(".editable-nilai").editable({'
    . '	success: function(response, newValue) {'
    . '					if (response.sukses) {'
    . '						$.fn.yiiGridView.update("membership-config-grid");'
    . '					}'
    . '				}  '
    . '});'
    . '});'
    . '$(".editable-nilai").editable({'
    . '	success: function(response, newValue) {'
    . '					if (response.sukses) {'
    . '						$.fn.yiiGridView.update("membership-config-grid");'
    . '					}'
    . '				}  '
    . '});', CClientScript::POS_END);

$this->breadcrumbs = [
    'Membership Config' => ['index'],
    'Index',
];

$this->boxHeader['small']  = 'Ahad Membership';
$this->boxHeader['normal'] = 'Konfigurasi Ahad Membership';
?>
<div class="row">
	<div class="small-12 columns">
		<?php
        $this->widget('BGridView', [
            'id'           => 'membership-config-grid',
            'dataProvider' => $model->search(),
            'filter'       => $model,
            'columns'      => [
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'nama',
                    'header'    => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                ],
                [
                    'class'     => 'BDataColumn',
                    'name'      => 'deskripsi',
                    'accesskey' => 'd',
                ],
                [
                    'name'      => 'nilai',
                    'value'     => [$this, 'renderEditableNilai'],
                    'type'      => 'raw',
                    'headerHtmlOptions' => ['style' => 'width:100px'],
                    'class'     => 'BDataColumn',
                    'header'    => 'Ni<span class="ak">l</span>ai',
                    'accesskey' => 'l',
                ]
            ],
        ]);
        ?>
	</div>
</div>
<?php
$this->menu = [
    ['itemOptions' => ['class' => 'divider'], 'label' => false],
    ['itemOptions' => ['class' => 'has-form hide-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-asterisk"></i> <span class="ak">I</span>ndex', 'url' => $this->createUrl('/membership/index'), 'linkOptions' => [
                'class'     => 'success button',
                'accesskey' => 'i'
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ],
    ['itemOptions' => ['class' => 'has-form show-for-small-only'], 'label' => false,
        'items'    => [
            ['label' => '<i class="fa fa-asterisk"></i>', 'url' => $this->createUrl('/membership/index'), 'linkOptions' => [
                'class' => 'success button',
            ]]
        ],
        'submenuOptions' => ['class' => 'button-group']
    ]
];
