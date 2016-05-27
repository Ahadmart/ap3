<div class="row collapse">
    <div class="small-10 columns">
        <select id="select-child">
            <?php
            // render _child_opt ;
            $this->renderPartial('_authitem_opt', array(
                'authItem' => $authItem
            ));
            ?>
        </select>
    </div>
    <div class="small-2 columns">
        <a href="#" id="tombol-assign" class="button postfix">Assign</a>
    </div>
</div>
<script>
    $("#tombol-assign").click(function () {
        console.log(jQuery("#select-child").val());
        dataString = 'child=' + jQuery("#select-child").val();
        $.fn.yiiGridView.update('auth-child-grid', {
            type: 'POST',
            data: dataString,
            url: "<?php echo $this->createUrl('assign', array('id' => $id)); ?>",
            success: function () {
                $.fn.yiiGridView.update('auth-child-grid');
                updateChildOpt();
            }
        });
        return false;
    });
    function updateChildOpt() {
        $("#select-child").load("<?php echo $this->createUrl('listauthitem', array('id' => $id)); ?>");
    }
</script>
<?php
$this->widget('BGridView', array(
    'id' => 'auth-child-grid',
    'dataProvider' => $model->search(),
    //'filter' => $model,
    'columns' => array(
        array(
            'class' => 'BDataColumn',
            'name' => 'child',
            'type' => 'raw',
            'value' =>
            function($data) {
                return '<a href="' . Yii::app()->controller->createUrl('ubah', array('id' => $data->child)) . '">' . $data->child . '</a>';
            }
                ),
                array(
                    'header' => '',
                    'value' => function($data) {
                        return '<span class="label">' . $data->child0->authTypeName . '</span>';
                    },
                    'type' => 'raw'
                ),
                array(
                    'class' => 'BButtonColumn',
                    'deleteButtonUrl' => 'Yii::app()->createUrl(\'' . $this->id . '/remove\', array(\'id\'=>\'' . $id . '\')).\'?child=\'.$data->child0->name',
                    'afterDelete' => 'function(link,success,data){ if(success) updateChildOpt(); }',
                ),
            ),
        ));

//eof