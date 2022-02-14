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
            $clientAPI = new AhadMembershipClient();
            $login = $clientAPI->login();
            if ($login == false) {
                $srUrl = MembershipConfig::model()->find('nama="url"');
                throw new CHttpException(404, 'Web Service ' . $srUrl['nilai'] . ' Not Found');
            }
            $login = json_decode($login, true);
            if (!empty($login['error']) || $login['statusCode'] != 200) {
                throw new CHttpException($login['statusCode'], $login['error']['type'] . ': ' . $login['error']['description']);
            }
            // echo "<pre>";
            // print_r($login);
            // echo "</pre>";
            $token = $login['data']['token'];
            $tokenConfig = MembershipConfig::model()->find('nama="bearer_token"');
            $tokenConfig->nilai = $token;
            $tokenConfig->update();
        }

        $this->render('registrasi', ['model' => $model]);
    }
}
