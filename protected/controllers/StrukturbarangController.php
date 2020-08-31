<?php

class StrukturbarangController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['deny', // deny guest
                'users' => ['guest'],
            ],
        ];
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $lv1 = new StrukturBarang('search');
        $lv1->unsetAttributes();  // clear any default values
        $lv1->setAttribute('level', 1); // default yang tampil
        $lv1->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil
        if (isset($_GET['StrukturBarang']) && $_GET['ajax'] == 'lv1-grid') {
            $lv1->attributes = $_GET['StrukturBarang'];
        }

        $lv2 = new StrukturBarang('search');
        $lv2->unsetAttributes();  // clear any default values
        $lv2->setAttribute('parent_id', 0); // default yang tampil
        $lv2->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil
        if (isset($_GET['StrukturBarang']) && $_GET['ajax'] == 'lv2-grid') {
            $lv2->attributes = $_GET['StrukturBarang'];
        }

        $lv3 = new StrukturBarang('search');
        $lv3->unsetAttributes();  // clear any default values
        $lv3->setAttribute('parent_id', 0); // default yang tampil
        $lv3->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil
        if (isset($_GET['StrukturBarang']) && $_GET['ajax'] == 'lv3-grid') {
            $lv3->attributes = $_GET['StrukturBarang'];
        }

        $this->render('index', [
            'lv1' => $lv1,
            'lv2' => $lv2,
            'lv3' => $lv3,
        ]);
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return StrukturBarang the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = StrukturBarang::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param StrukturBarang $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'struktur-barang-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionTambahLv1()
    {
        if (Yii::app()->request->isPostRequest) {
            $lv1           = Yii::app()->request->getPost('nama');
            $sbModel       = new StrukturBarang();
            $sbModel->nama = $lv1;
            if (!$sbModel->save()) {
                echo "Gagal Simpan";
                print_r($sbModel->errors);
            } else {
                echo $lv1;
            }
        }
    }

    public function actionUpdateUrutan()
    {
        $r = ['sukses' => false];
        if (Yii::app()->request->getPost('ganti')) {
            foreach (Yii::app()->request->getPost('items') as $item) {
                $key         = $item[0];
                $urutan      = $item[1];
                $sb         = StrukturBarang::model()->findByPk($key);
                $sb->urutan = $urutan;

                if ($sb->save()) {
                    $r = ['sukses' => true];
                }
            }
        }
        $this->renderJSON($r);
    }

    public function actionTelahDipilihLv1()
    {
        $r = ['sukses' => false];
        if (Yii::app()->request->getPost('dipilih')) {
            if (!is_null(Yii::app()->request->getPost('id'))) {
                $lv1Id = Yii::app()->request->getPost('id')[0];

                $lv2 = new StrukturBarang('search');
                $lv2->unsetAttributes();  // clear any default values
                $lv2->setAttribute('parent_id', $lv1Id); // default yang tampil
                $lv2->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil

                $this->renderPartial('_grid2', ['lv2' => $lv2]);
            }
        }
        if (isset($_GET['StrukturBarang']) && $_GET['ajax'] == 'lv1-grid') {
            $lv1             = new StrukturBarang('search');
            $lv1->unsetAttributes();  // clear any default values
            $lv1->attributes = $_GET['StrukturBarang'];
            $lv1->parent_id  = $_GET['parent_id'];
            $lv1->level      = 1;
            $this->renderPartial('_grid1', ['lv1' => $lv1]);
        }
    }

    public function actionTambahLv2()
    {
        if (Yii::app()->request->isPostRequest) {
            $lv2                = Yii::app()->request->getPost('nama');
            $parentId            = Yii::app()->request->getPost('parent');
            $sbModel            = new StrukturBarang();
            $sbModel->nama      = $lv2;
            $sbModel->parent_id = $parentId;
            $sbModel->level     = 2;
            if (!$sbModel->save()) {
                echo "Gagal Simpan";
                print_r($sbModel->errors);
            } else {
                $lv2Model = new StrukturBarang('search');
                $lv2Model->unsetAttributes();  // clear any default values
                $lv2Model->setAttribute('parent_id', $parentId); // default yang tampil
                $lv2Model->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil

                $this->renderPartial('_grid2', [
                    'lv2' => $lv2Model,
                ]);
            }
        }
    }

    public function actionTelahDipilihLv2()
    {
        $r = ['sukses' => false];
        if (Yii::app()->request->getPost('dipilih')) {
            if (!is_null(Yii::app()->request->getPost('id'))) {
                $lv2Id = Yii::app()->request->getPost('id')[0];

                $lv3 = new StrukturBarang('search');
                $lv3->unsetAttributes();  // clear any default values
                $lv3->setAttribute('parent_id', $lv2Id);
                $lv3->setAttribute('status', StrukturBarang::STATUS_PUBLISH);

                $this->renderPartial('_grid3', ['lv3' => $lv3]);
            }
        }
        if (isset($_GET['StrukturBarang']) && $_GET['ajax'] == 'lv2-grid') {
            $lv2             = new StrukturBarang('search');
            $lv2->unsetAttributes();  // clear any default values
            $lv2->attributes = $_GET['StrukturBarang'];
            $lv2->parent_id  = $_GET['parent_id'];
            $lv2->level      = 2;
            $this->renderPartial('_grid2', ['lv2' => $lv2]);
        }
    }

    public function actionTambahLv3()
    {
        if (Yii::app()->request->isPostRequest) {
            $lv3                = Yii::app()->request->getPost('nama');
            $parentId            = Yii::app()->request->getPost('parent');
            $sbModel            = new StrukturBarang();
            $sbModel->nama      = $lv3;
            $sbModel->parent_id = $parentId;
            $sbModel->level     = 3;
            if (!$sbModel->save()) {
                echo "Gagal Simpan";
                print_r($sbModel->errors);
            } else {
                $lv3Model = new StrukturBarang('search');
                $lv3Model->unsetAttributes();  // clear any default values
                $lv3Model->setAttribute('parent_id', $parentId); // default yang tampil
                $lv3Model->setAttribute('status', StrukturBarang::STATUS_PUBLISH); // default yang tampil

                $this->renderPartial('_grid3', [
                    'lv3' => $lv3Model,
                ]);
            }
        }
    }

    public function actionUpdateNama()
    {
        if (Yii::app()->request->isPostRequest) {
            $nama        = Yii::app()->request->getPost('value');
            $id          = Yii::app()->request->getPost('pk');
            $model       = StrukturBarang::model()->findByPk($id);
            $model->nama = $nama;
            $model->update();
        }
    }

    public function actionRenderGrid()
    {
        $level  = Yii::app()->request->getPost('level');
        $parent = Yii::app()->request->getPost('parent');
        switch ($level) {
            case 1:
                $model = new StrukturBarang('search');
                $model->unsetAttributes();
                $model->setAttribute('level', 1);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid1', ['lv1' => $model]);
                break;
            case 2:
                $model = new StrukturBarang('search');
                $model->unsetAttributes();  // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid2', ['lv2' => $model]);
                break;
            case 3:
                $model = new StrukturBarang('search');
                $model->unsetAttributes();  // clear any default values
                $model->setAttribute('parent_id', $parent);
                $model->setAttribute('status', StrukturBarang::STATUS_PUBLISH);
                $this->renderPartial('_grid3', ['lv3' => $model]);
                break;
        }
    }

}
