<?php

class BaseController extends CController {

	public function getTheme() {
		if (!isset(Yii::app()->user->id)) {
			return NULL;
		}

		$user = User::model()->findByPk(Yii::app()->user->id);

		if (is_null($user->theme_id)) {
			return NULL;
		}

		$theme = Theme::model()->findByPk($user->theme_id);

		return is_null($theme) ? NULL : $theme->nama;
	}

}
