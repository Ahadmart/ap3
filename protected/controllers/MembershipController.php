<?php

class MembershipController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionRegistrasi()
    {
        $model = new MembershipRegistrationForm;
        $this->render('registrasi', ['model' => $model]);
    }

    public function actionProsesRegistrasi()
    {
        if (isset($_POST['noTelp']) && isset($_POST['namaLengkap'])) {
            $form = [
                'noTelp'       => $_POST['noTelp'],
                'namaLengkap'  => $_POST['namaLengkap'],
                'tanggalLahir' => date_format(date_create_from_format('d-m-Y', $_POST['tanggalLahir']), 'Y-m-d'),
                'pekerjaan'    => $_POST['pekerjaan'],
                'alamat'       => $_POST['alamat'],
                'keterangan'   => $_POST['keterangan'],
                'userName'     => Yii::app()->user->namaLengkap,
            ];
            $clientAPI = new AhadMembershipClient();
            echo $clientAPI->registrasi($form);
        }
    }

    /**
     * Displays a particular model.
     * @param string $nomor Nomor member yang akan di display
     */
    public function actionViewByNomor($nomor)
    {
        $clientAPI = new AhadMembershipClient();
        $r         = json_decode($clientAPI->view($nomor));
    }
}
