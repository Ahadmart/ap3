<?php
/* @var $this AssignmentController */

$this->breadcrumbs = array(
    'Assignment',
);
$this->boxHeader['small'] = 'Assignment';
$this->boxHeader['normal'] = 'User Assignment';
?>
<div class="row">
    <div class="small-12 columns">
        <?php
        $this->widget('BGridView', array(
            'id' => 'auth-assignment-grid',
            'dataProvider' => $model->listUsers(),
            'filter' => $model,
            'columns' => array(
                //'nama',
                array(
                    'class' => 'BDataColumn',
                    'name' => 'nama',
                    'header' => '<span class="ak">N</span>ama',
                    'accesskey' => 'n',
                    'type' => 'raw',
                    /*
                      'value' => function($data) {
                      return '<a href="' . Yii::app()->controller->createUrl('ubah', array('userid' => $data->id)) . '">' . $data->nama . '</a>';
                      }
                      ),
                     * 
                     */
                    'value' => array($this, 'renderLinkToUbah'),
                ),
                'nama_lengkap',
                array(
                    'header' => 'Assigned Item(s)',
                    'type' => 'raw', /*

                      'value' => function($data) {
                      $string = '';
                      $assignedList = AuthAssignment::model()->assignedList($data->id);
                      //<span class="label">Regular Label</span>
                      foreach ($assignedList as $item) {
                      $string.= '<span class="secondary label">' . $item['itemname'] . '</span><span class="label">' . $item['typename'] . '</span><br />';
                      }
                      return $string;
                      },
                     * 
                     */
                    'value' => array($this, 'renderAssignedItem')
                ),
            ),
        ));
        ?>
    </div>
</div>
