<?php

class BaseController extends CController
{

    public function getTheme()
    {
        if (!isset(Yii::app()->user->id)) {
            $themeId = Theme::model()->getCookies();
            if (is_null($themeId)) {
                return NULL;
            }
            $theme = Theme::model()->findByPk($themeId);
        } else {

            $user = User::model()->findByPk(Yii::app()->user->id);

//        if (is_null($user->theme_id)) {
//            $theme= NULL;
//        } else {
            $theme = Theme::model()->findByPk($user->theme_id);
//        }
        }
        return is_null($theme) ? NULL : $theme->nama;
    }

    /**
     * Return data to browser as JSON and end application.
     * @param array $data
     */
    protected function renderJSON($data)
    {
        header('Content-type: application/json');
        echo CJSON::encode($data);

        foreach (Yii::app()->log->routes as $route) {
            if ($route instanceof CWebLogRoute) {
                $route->enabled = false; // disable any weblogroutes
            }
        }
        Yii::app()->end();
    }

}
