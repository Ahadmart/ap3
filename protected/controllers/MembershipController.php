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

        if (isset($_POST['MembershipRegistrationForm'])) {
            $form = $_POST['MembershipRegistrationForm'];
            $form['tanggalLahir'] = date_format(date_create_from_format('d-m-Y', $form['tanggalLahir']), 'Y-m-d'); // Ubah dari d-m-Y ke Y-m-d
            $form['userName'] = Yii::app()->user->namaLengkap;
            $clientAPI = new AhadMembershipClient();
            $r = $clientAPI->registrasi($form);            
        }

        $this->render('registrasi', ['model' => $model]);
    }

    /**
     * Displays a particular model.
     * @param string $nomor Nomor member yang akan di display
     */
    public function actionViewByNomor($nomor)
    {
        $clientAPI = new AhadMembershipClient();
        $r = json_decode($clientAPI->view($nomor));
        
    }

}
