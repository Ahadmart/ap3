<?php

class MembershipController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionRegistrasi()
    {
        $this->layout = '//layouts/box_kecil';
        $model = new MembershipRegistrationForm;
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

        $this->layout = '//layouts/box_kecil';
        $this->render('view', ['model' => $profil]);
    }

    public function actionUbah($id)
    {
        $clientAPI = new AhadMembershipClient();
        $data      = json_decode($clientAPI->view($id));
        if ($data->statusCode != 200) {
            throw new CHttpException($data->statusCode, $data->error->type . ': ' . $data->error->description);
        }
        $profil    = $data->data->profil;
        $this->layout = '//layouts/box_kecil';
        $this->render('ubah',['model'=>$profil]);
    }
}
