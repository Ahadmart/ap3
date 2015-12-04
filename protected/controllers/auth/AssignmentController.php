<?php

class AssignmentController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    //public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('deny', // deny guest
                'users' => array('guest'),
            ),
        );
    }

    public function actionIndex()
    {
        $model = new AuthAssignment('search');

        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['AuthAssignment']))
            $model->attributes = $_GET['AuthAssignment'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionUbah($userid)
    {
        $this->layout = '//layouts/box_kecil';
        $model = new AuthAssignment('search');
        $model->unsetAttributes();
        $model->setAttribute('userid', '=' . $userid);

        $user = User::model()->findByPk($userid);

        $this->render('ubah', array(
            'user' => $user,
            'model' => $model,
            'authItem' => AuthItem::model()->listNotAssignedItem($userid)
        ));
    }

    public function actionListAuthItem($userid)
    {
        $this->renderPartial('../item/_authitem_opt', array(
            'authItem' => AuthItem::model()->listNotAssignedItem($userid)
        ));
    }

    /*
     * Assign an item to userId
     */

    public function actionAssign($userid)
    {
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'auth-assigned-grid' && isset($_POST['item'])) {
            $item = $_POST['item'];
            $auth = Yii::app()->authManager;
            $auth->assign($item, $userid);
            echo 'Assign Item Status: OK';
        }
    }

    /*
     * Revoke an Item
     */

    public function actionRevoke($userid, $item)
    {
        if (isset($_GET['ajax']) && $_GET['ajax'] === 'auth-assigned-grid') {
            $auth = Yii::app()->authManager;
            $auth->revoke($item, $userid);
            echo 'Remove Item Status: OK';
        }
    }

    public function renderAssignedItem($data)
    {
        $string = '';
        $assignedList = AuthAssignment::model()->assignedList($data->id);
        //<span class="label">Regular Label</span>
        foreach ($assignedList as $item) {
            $string.= '<span class="secondary label">' . $item['itemname'] . '</span><span class="label">' . $item['typename'] . '</span><br />';
        }
        return $string;
    }

    public function renderLinkToUbah($data)
    {
        return '<a href="' . Yii::app()->controller->createUrl('ubah', array('userid' => $data->id)) . '">' . $data->nama . '</a>';
    }

}
