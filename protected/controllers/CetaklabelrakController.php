<?php

class CetaklabelrakController extends Controller
{

    public function actionIndex()
    {
        $modelForm = new CetakLabelRakForm;
        $this->render('index', array(
            'modelForm' => $modelForm
        ));
    }

}
