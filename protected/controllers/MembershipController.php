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
                'umur'         => $_POST['umur'],
                'pekerjaanId'  => $_POST['pekerjaanId'],
                'alamat'       => $_POST['alamat'],
                'keterangan'   => $_POST['keterangan'],
                'userName'     => Yii::app()->user->namaLengkap,
            ];
            $model = new MembershipRegistrationForm();
            $model->setAttributes($form);
            if ($model->validate()) {
                $clientAPI = new AhadMembershipClient();
                echo $clientAPI->registrasi($model->getAttributes());
            } else {
                $error = [
                    'statusCode' => 400,
                    'error'      => [
                        'type'        => 'BAD_REQUEST',
                        'description' => 'MembershipRegistrationForm: Nomor Telp/Nama/Umur tidak boleh kosong'
                    ]
                ];
                // Yii::log(print_r($form, true), 'info');
                // Yii::log(print_r($model->getAttributes(), true), 'info');
                echo json_encode($error);
            }
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
        $profil->umur         = null;
        if (!empty($profil->tanggalLahir)) {
            $tglLahir             = date_format(date_create_from_format('Y-m-d', $profil->tanggalLahir), 'd-m-Y');
            $tgl1                 = new DateTime($tglLahir);
            $now                  = new DateTime();
            $interval             = $tgl1->diff($now);
            $profil->tanggalLahir = $tglLahir;
            $profil->umur         = $interval->y;
        } else {
            $profil->tanggalLahir = '';
        }
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
                'umur'         => $_POST['umur'],
                'umurOld'      => $_POST['umurOld'],
                'pekerjaanId'  => $_POST['pekerjaanId'],
                'alamat'       => $_POST['alamat'],
                'keterangan'   => $_POST['keterangan'],
                'userName'     => Yii::app()->user->namaLengkap,
            ];
            // $this->renderJSON($data);
            $model = new MembershipRegistrationForm();
            $model->setAttributes($data);
            if ($model->validate()) {
                $clientAPI = new AhadMembershipClient();
                echo $clientAPI->update($id, $model->getAttributes());
            } else {
                $error = [
                    'statusCode' => 400,
                    'error'      => [
                        'type'        => 'BAD_REQUEST',
                        'description' => 'MembershipRegistrationForm: Nomor Telp/Nama/Umur tidak boleh kosong'
                    ]
                ];
                echo json_encode($error);
            }
        }
    }

    public function actionCari()
    {
        $data = $_POST['data'];

        $clientAPI = new AhadMembershipClient();
        echo $clientAPI->cari($data);
    }

    public function actionReportMutasiPoin()
    {
        $data           = [];
        $data['nomor']  = $_POST['nomor'];
        $data['dari']   = !empty($_POST['dari']) ? date_format(date_create_from_format('d-m-Y', $_POST['dari']), 'Y-m-d') : '';
        $data['sampai'] = !empty($_POST['sampai']) ? date_format(date_create_from_format('d-m-Y', $_POST['sampai']), 'Y-m-d') : '';

        $clientAPI = new AhadMembershipClient();
        echo $clientAPI->mutasiPoin($data);
    }

    public function actionReportMutasiKoin()
    {
        $data           = [];
        $data['nomor']  = $_POST['nomor'];
        $data['dari']   = !empty($_POST['dari']) ? date_format(date_create_from_format('d-m-Y', $_POST['dari']), 'Y-m-d') : '';
        $data['sampai'] = !empty($_POST['sampai']) ? date_format(date_create_from_format('d-m-Y', $_POST['sampai']), 'Y-m-d') : '';

        $clientAPI = new AhadMembershipClient();
        echo $clientAPI->mutasiKoin($data);
    }
}
