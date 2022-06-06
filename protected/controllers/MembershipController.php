<?php

class MembershipController extends Controller
{
    public $layout = '//layouts/box_kecil';

    public function actionIndex()
    {
        $this->layout = '//layouts/box';
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
            $kodeNegara = '62'; // Saat ini dibuat tetap 62 (indonesia)
            $tglLahir   = !empty($_POST['tanggalLahir']) ? date_format(date_create_from_format('d-m-Y', $_POST['tanggalLahir']), 'Y-m-d') : '';
            $form       = [
                'kodeNegara'   => $kodeNegara,
                'noTelp'       => $_POST['noTelp'],
                'namaLengkap'  => $_POST['namaLengkap'],
                'jenisKelamin' => $_POST['jenisKelamin'],
                'tanggalLahir' => $tglLahir,
                'pekerjaanId'  => $_POST['pekerjaanId'],
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

        $profil               = $data->data->profil;
        $tglLahir             = !empty($profil->tanggalLahir) ? date_format(date_create_from_format('Y-m-d', $profil->tanggalLahir), 'd-m-Y') : '';
        $profil->tanggalLahir = $tglLahir;
        $jenisKelamin         = empty($profil->jenisKelamin) || $profil->jenisKelamin == MembershipRegistrationForm::JENIS_KELAMIN_PRIA ? 'Pria' : 'Wanita';
        $profil->jenisKelamin = $jenisKelamin;

        $this->render('view', ['model' => $profil]);
    }

    public function actionUbah($id)
    {
        $clientAPI = new AhadMembershipClient();
        $data      = json_decode($clientAPI->view($id));
        if ($data->statusCode != 200) {
            throw new CHttpException($data->statusCode, $data->error->type . ': ' . $data->error->description);
        }
        $profil               = $data->data->profil;
        $tglLahir             = !empty($profil->tanggalLahir) ? date_format(date_create_from_format('Y-m-d', $profil->tanggalLahir), 'd-m-Y') : '';
        $profil->tanggalLahir = $tglLahir;

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
            $kodeNegara = '62';
            $tglLahir   = !empty($_POST['tanggalLahir']) ? date_format(date_create_from_format('d-m-Y', $_POST['tanggalLahir']), 'Y-m-d') : '';
            $data       = [
                'kodeNegara'   => $kodeNegara,
                'noTelp'       => $_POST['noTelp'],
                'namaLengkap'  => $_POST['namaLengkap'],
                'jenisKelamin' => $_POST['jenisKelamin'],
                'tanggalLahir' => $tglLahir,
                'pekerjaanId'  => $_POST['pekerjaanId'],
                'alamat'       => $_POST['alamat'],
                'keterangan'   => $_POST['keterangan'],
                'userName'     => Yii::app()->user->namaLengkap,
            ];
            // $this->renderJSON($data);
            $clientAPI = new AhadMembershipClient();
            echo $clientAPI->update($id, $data);
        }
    }

    public function actionCari()
    {
        $data = $_POST['data'];

        $clientAPI = new AhadMembershipClient();
        echo $clientAPI->cari($data);
    }

    public function actionReportMutasi()
    {
        $data           = [];
        $data['nomor']  = $_POST['nomor'];
        $data['dari']   = !empty($_POST['dari']) ? date_format(date_create_from_format('d-m-Y', $_POST['dari']), 'Y-m-d') : '';
        $data['sampai'] = !empty($_POST['sampai']) ? date_format(date_create_from_format('d-m-Y', $_POST['sampai']), 'Y-m-d') : '';

        $clientAPI = new AhadMembershipClient();
        echo $clientAPI->mutasiPoin($data);
    }
}
