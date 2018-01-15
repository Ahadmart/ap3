<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class PublicController extends BaseController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/polos';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu      = [];
    public $boxHeader = ['normal' => '', 'small' => ''];

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = [];

    protected function beforeAction($action)
    {
        $theme            = $this->getTheme();
        Yii::app()->theme = is_null($theme) ? 'default_dark' : $theme;
        return parent::beforeAction($action);
    }

}
