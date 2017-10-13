<?php

class EditbarangController extends Controller
{

    public function actionIndex()
    {
        $model = new Barang('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Barang']))
            $model->attributes = $_GET['Barang'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

}
