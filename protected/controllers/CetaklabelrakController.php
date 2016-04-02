<?php

class CetaklabelrakController extends Controller
{

    public function actionIndex()
    {
        $modelForm = new CetakLabelRakForm;

        $profil = new Profil('search');
        $profil->unsetAttributes();  // clear any default values
        if (isset($_GET['Profil'])) {
            $profil->attributes = $_GET['Profil'];
        }

        $rak = new RakBarang('search');
        $rak->unsetAttributes();
        if (isset($_GET['RakBarang'])) {
            $rak->attributes = $_GET['RakBarang'];
        }

        $labelCetak = new LabelRakCetak('search');
        $labelCetak->unsetAttributes();

        $this->render('index', array(
            'modelForm' => $modelForm,
            'profil' => $profil,
            'rak' => $rak,
            'labelCetak' => $labelCetak
        ));
    }

    public function actionPilihProfil($id)
    {
        $profil = Profil::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'nama' => $profil->nama,
            'alamat1' => $profil->alamat1,
        );
        $this->renderJSON($return);
    }

    public function actionPilihRak($id)
    {
        $rak = RakBarang::model()->findByPk($id);
        $return = array(
            'id' => $id,
            'nama' => $rak->nama,
        );
        $this->renderJSON($return);
    }

    public function actionTambahkanBarang()
    {
        $return = array(
            'sukses' => false,
            'error' => [
                'code' => 500,
                'msg' => 'Sempurnakan input!'
            ]
        );
        if (isset($_POST['CetakLabelRakForm'])) {
            $cetakLabelRakForm = new CetakLabelRakForm;
            $cetakLabelRakForm->attributes = $_POST['CetakLabelRakForm'];
            $rowAffected = $cetakLabelRakForm->inputBarangKeCetak();
            if (!is_null($rowAffected)) {
                $return = array(
                    'sukses' => true,
                    'rowAffected' => $rowAffected
                );
            }
        }
        $this->renderJSON($return);
    }

}
