<?php

$this->widget('BGridView', array(
    'id' => 'auth-assigned-grid',
    'dataProvider' => $model->search(),
//	 'filter' => $model,
    'columns' => array(
        'itemname',
        array(
            'header' => '',
            'value' => function($data) {
                return '<span class="label">' . $data->authTypeName . '</span>';
            },
            'type' => 'raw'
        ),
        array(
            'class' => 'BButtonColumn',
            'headerHtmlOptions' => array('style' => 'width:50px'),
            'deleteButtonUrl' => 'Yii::app()->createUrl(\'' . $this->id . '/revoke\', array(\'userid\'=>\'' . $user->id . '\',\'item\'=>$data->itemname))',
            'deleteButtonLabel' => '<i class="fa fa-eject"></i>',
            'deleteButtonOptions' => array('title' => 'Revoke'),
            'afterDelete' => 'function(link,success,data){ if(success) updateItemOpt(); }',
        ),
    ),
));

//eof