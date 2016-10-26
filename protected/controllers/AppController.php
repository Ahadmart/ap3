<?php

class AppController extends PublicController
{

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        $this->layout = '//layouts/box';
        $homeShowNpls = true;

        $rekapAds = null;
        if ($homeShowNpls) {
            $tabelRekapAds = Yii::app()->db->schema->getTable('rekap_ads');
            if (!is_null($tabelRekapAds)) {
                $rekapAds = new RekapAds('search');
                $rekapAds->unsetAttributes();
                /* Tampilkan yang sisa hari < 7 hari */
                $rekapAds->setAttribute('sisa_hari', '< 7');
            }
        }

        if (Yii::app()->user->isGuest) {
            $this->redirect($this->createUrl('/app/login'));
        } else {
            $roles = AuthAssignment::model()->assignedList(Yii::app()->user->id);
            $this->render('index', array(
                'roles' => $roles,
                'rekapAds' => $rekapAds
            ));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $this->layout = '//layouts/box_kecil';
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        // if it is ajax validation request
        /*
          if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        /* Simpan theme ke cookies */
        $user = User::model()->findByPk(Yii::app()->user->id);        
        $theme = Theme::model()->findByPk($user->theme_id);
        $theme->toCookies();
        
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /* Untuk tabel NPLS */

    public function renderLinkToViewBarang($data)
    {
        $return = '';
        if (isset($data->barang)) {
            $return = '<a target="_blank" href="' .
                    $this->createUrl('barang/view', ['id' => $data->barang_id]) . '">' .
                    $data->barang->barcode . '</a>';
        }
        return $return;
    }

}
