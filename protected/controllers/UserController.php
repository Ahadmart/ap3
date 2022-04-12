<?php

class UserController extends Controller
{

    public $layout = '//layouts/box_kecil';

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

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {

        require_once __DIR__ . '/../vendors/password_compat/password.php';
        $model = new User;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            $model->attributes = $_POST['User'];
            if ($model->save()) {
                $this->redirect(array('view', 'id' => $model->id));
            }
        }

        $this->render('tambah', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUbah($id)
    {

        require_once __DIR__ . '/../vendors/password_compat/password.php';
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['User'])) {
            //$model->unsetAttributes();
            //$model->setAttributes($_POST['User']);
            $model->attributes = $_POST['User'];
            if ($model->save(true, ['nama', 'nama_lengkap', 'password', 'theme_id', 'menu_id'])) {
                $this->redirect(array('view', 'id' => $id));
            }
        }

        $this->render('ubah', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionHapus($id)
    {
        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $this->layout = '//layouts/box';
        $model = new User('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['User']))
            $model->attributes = $_GET['User'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return User the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = User::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param User $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function renderLinkToAssignment($data)
    {
        $string = '';
        $assignedList = AuthAssignment::model()->assignedList($data->id);
        if (empty($assignedList)) {
            $string = '<span class="not-set">(not set)</span>';
        }
        foreach ($assignedList as $item) {
            $string .= '<span class="label success">' . $item['itemname'] . '</span><span class="label default">' . $item['typename'] . '</span><br />';
        }
        foreach ($assignedList as $item) {
            //$string .= ' ' . $item['itemname'];
        }
        return CHtml::link($string, $this->createUrl('assignment', ['userid' => $data->id]));
    }

    public function actionAssignment($userid)
    {
        //$this->layout = '//layouts/box_medium';
        $model = new AuthAssignment('search');
        $model->unsetAttributes();
        $model->setAttribute('userid', '=' . $userid);

        $user = User::model()->findByPk($userid);

        $this->render('assignment', array(
            'user' => $user,
            'model' => $model,
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

}
