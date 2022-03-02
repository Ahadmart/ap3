<?php

class MembershipController extends Controller
{
    public $layout = '//layouts/box_kecil';
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionRegistrasi()
    {
        $model        = new MembershipRegistrationForm;
        $this->render('registrasi', ['model' => $model]);
    }

    public function actionProsesRegistrasi()
    {
        if (isset($_POST['noTelp']) && isset($_POST['namaLengkap'])) {
            $tglLahir = !empty($_POST['tanggalLahir']) ? date_format(date_create_from_format('d-m-Y', $_POST['tanggalLahir']), 'Y-m-d') : '';
            $form     = [
                'noTelp'       => $_POST['noTelp'],
                'namaLengkap'  => $_POST['namaLengkap'],
                'tanggalLahir' => $tglLahir,
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
     * @param string $id Nomor member yang akan di display
     */
    public function actionView($id)
    {
        $clientAPI = new AhadMembershipClient();
        $data      = json_decode($clientAPI->view($id));
        if ($data->statusCode != 200) {
            throw new CHttpException($data->statusCode, $data->error->type . ': ' . $data->error->description);
        }

        $profil                = $data->data->profil;
        $tglLahir              = !empty($profil->tanggal_lahir) ? date_format(date_create_from_format('Y-m-d', $profil->tanggal_lahir), 'd-m-Y') : '';
        $profil->tanggal_lahir = $tglLahir;

        $this->render('view', ['model' => $profil]);
    }

    public function actionUbah($id)
    {
        $clientAPI = new AhadMembershipClient();
        $data      = json_decode($clientAPI->view($id));
        if ($data->statusCode != 200) {
            throw new CHttpException($data->statusCode, $data->error->type . ': ' . $data->error->description);
        }
        $profil                = $data->data->profil;
        $tglLahir              = !empty($profil->tanggal_lahir) ? date_format(date_create_from_format('Y-m-d', $profil->tanggal_lahir), 'd-m-Y') : '';
        $profil->tanggal_lahir = $tglLahir;
        $this->render('ubah', ['model' => $profil]);
    }

    /**
     * ActionProsesUbah function
     *
     * @param string $id Nomor member
     * @return json
     */
    public function actionProsesUbah($id)
    {
        if (isset($_POST['noTelp']) && isset($_POST['namaLengkap'])) {
            $tglLahir = !empty($_POST['tanggalLahir']) ? date_format(date_create_from_format('d-m-Y', $_POST['tanggalLahir']), 'Y-m-d') : '';
            $data     = [
                'noTelp'       => $_POST['noTelp'],
                'namaLengkap'  => $_POST['namaLengkap'],
                'tanggalLahir' => $tglLahir,
                'pekerjaan'    => $_POST['pekerjaan'],
                'alamat'       => $_POST['alamat'],
                'keterangan'   => $_POST['keterangan'],
                'userName'     => Yii::app()->user->namaLengkap,
            ];
            // $this->renderJSON($data);
            $clientAPI = new AhadMembershipClient();
            echo $clientAPI->update($id, $data);
        }
    }
}
