<?php

class MenuController extends Controller
{

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionTambah()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new Menu;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Menu'])) {
            $model->attributes = $_POST['Menu'];
            if ($model->save())
                $this->redirect(['ubah', 'id' => $model->id]);
        }

        $this->render('tambah', [
            'model' => $model,
        ]);
    }

    /**
     * Updates a particular model.
     * @param integer $id the ID of the model to be updated
     * @param integer $subId ID of the model (Sub Menu) to be updated
     */
    public function actionUbah($id, $subId = null)
    {
        $model = $this->loadModel($id);

        if (empty($subId)) {
            $subMenuModel = new Menu;
        } else {
            $subMenuModel = $this->loadModel($subId);
        }

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Menu']) && !isset($_GET['ajax'])) {
            $model->attributes = $_POST['Menu'];
            if ($model->save()) {
                // Kirim pesan sukses
            }
        }

        $subMenuList = $model->listChild;
        $subMenuTreeList = $model->treeListChild;
        /*
          echo '<pre>';
          echo 'menu:';
          print_r($subMenuList);
          echo '</pre>';
         */
        $subMenuGrid = new Menu('search');
        $subMenuGrid->unsetAttributes();
        if (isset($_GET['Menu']) && isset($_GET['ajax']) && $_GET['ajax'] == 'menu-grid') {
            $subMenuGrid->attributes = $_GET['Menu'];
        }
        $subMenuGrid->root_id = $id;

        $this->render('ubah', [
            'model' => $model,
            'subMenuModel' => $subMenuModel,
            'subMenuList' => $subMenuList,
            'subMenuTreeList' => $subMenuTreeList,
            'subMenuGrid' => $subMenuGrid
        ]);
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
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['index']);
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new Menu('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Menu']))
            $model->attributes = $_GET['Menu'];
        $model->parent_id = NULL;
        $model->status = Menu::STATUS_PUBLISH;

        $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Menu the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Menu::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Menu $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'menu-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function renderLinkToUbah($data)
    {
        $return = '';
        if (isset($data->nama)) {
            $return = '<a href="' .
                    $this->createUrl('ubah', ['id' => $data->id]) . '">' .
                    $data->nama . '</a>';
        }
        return $return;
    }

    public function renderLinkToUbahSub($data)
    {
        $return = '';
        if (isset($data->nama)) {
            $return = '<a href="' .
                    $this->createUrl('ubah', ['id' => $data->root_id, 'subId' => $data->id]) . '">' .
                    $data->nama . '</a>';
        }
        return $return;
    }

    public function actionTambahSubMenu($id)
    {
        if (empty($_POST['Menu']['id'])) {
            $menu = new Menu;
            $menu->unsetAttributes();  // clear any default values
        } else {
            $menu = $this->loadModel($_POST['Menu']['id']);
        }
        if (isset($_POST['Menu'])) {
            $postData = $_POST['Menu'];
            unset($postData['id']); // Tidak perlu (lagi), dihilangkan saja
            $menu->attributes = $postData;
        }
        if (empty($postData['parent_id'])) {
            $menu->parent_id = $id;
        }
        $menu->root_id = $id;
        $return = ['sukses' => false];
        if ($menu->save()) {
            $return = ['sukses' => true];
        } else {
            $return = ['sukses' => false, 'msg' => serialize($menu->errors)];
        }
        $this->renderJSON($return);
    }

    public function actionRenderMenuPreview($id)
    {
        $model = $this->loadModel($id);

        $this->renderPartial('_menu_preview', [
            'subMenuTreeList' => $model->treeListChild
        ]);
    }

    public function actionDeleteSubMenu($id)
    {
        $this->renderJSON(['sukses' => $this->loadModel($id)->delete()]);
    }

}
