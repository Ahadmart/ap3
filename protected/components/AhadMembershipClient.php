<?php
class AhadMembershipClient
{
    public $baseUrl;
    public $token;

    public function __construct()
    {
        // $configUrl = MembershipConfig::model()->find('nama="url"');
        // $this->url = $configUrl->nilai;
        // $configToken = MembershipConfig::model()->find('nama="token"');
        // $this->token = $configToken->nilai;

        $membershipConfig = MembershipConfig::model()->findAll();
        $configArr        = [];
        foreach ($membershipConfig as $config) {
            $configArr[$config->nama] = $config->nilai;
        }
        $this->baseUrl = $configArr['url'];
        $this->token   = $configArr['bearer_token'];

        // Periksa token. Login jika belum ada, atau expired
        if (empty($this->token) || $this->isTokenExpired()) {
            $credentials = [
                'kode'     => $configArr['login.kode'],
                'nama'     => $configArr['login.nama'],
                'pwd'      => UnsafeCrypto::decrypt($configArr['login.password'], UnsafeCrypto::AHADMEMBERSHIP_KEY, true),
                'userName' => Yii::app()->user->namaLengkap,
            ];
            $login = json_decode($this->login($credentials), true);

            // Yii::log("Hasil login: " . print_r($login,true));
            // Update token
            $this->token = $login['data']['token'];
            // Update token di database
            $tokenConfig        = MembershipConfig::model()->find('nama="bearer_token"');
            $tokenConfig->nilai = $this->token;
            $tokenConfig->update();
        }
    }

    private function errorHandle($r)
    {
        $err = [];
        if ($r == false) {
            $srUrl = MembershipConfig::model()->find('nama="url"');
            // throw new CHttpException(404, 'Web Service ' . $srUrl['nilai'] . ' Not Found');
            $err['statusCode']           = 404;
            $err['error']['type']        = 'CONNECTION_ERROR';
            $err['error']['description'] = 'Web Service ' . $srUrl['nilai'] . ' Not Found';
            return json_encode($err);
        }
        return $r;
        // $result = json_decode($r, true);
        // if (!empty($result['error']) || $result['statusCode'] != 200) {
        // throw new CHttpException($result['statusCode'], $result['error']['type'] . ': ' . $result['error']['description']);
        // }
    }

    private function postRequest($url, $data, $login = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'content-type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $this->token,
        ];
        if ($login) {
            $headers = [
                'content-type: application/json; charset=utf-8',
            ];
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        // $response = curl_getinfo($ch);
        curl_close($ch);
        // $httpResponseCode = $response['http_code'];
        return $this->errorHandle($r);
    }

    private function getRequest($url)
    {
        $ch = curl_init($url);
        $headers = [
            'Authorization: Bearer ' . $this->token,
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        // Uncomment untuk nge-log ke requests.log
        // ob_start();
        // echo substr($r, 0, 100) . '.....';
        // $input = ob_get_contents();
        // ob_end_clean();
        // file_put_contents(
        //     'requests.log',
        //     $input . PHP_EOL,
        //     FILE_APPEND
        // );
        return $this->errorHandle($r);
    }

    private function putRequest($url, $data, $login = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'content-type: application/json; charset=utf-8',
            'Authorization: Bearer ' . $this->token,
        ];
        if ($login) {
            $headers = [
                'content-type: application/json; charset=utf-8',
            ];
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        // $response = curl_getinfo($ch);
        curl_close($ch);
        // $httpResponseCode = $response['http_code'];
        return $this->errorHandle($r);
    }

    public function login($credentials)
    {
        $url = $this->baseUrl . '/toko/login';
        return $this->postRequest($url, $credentials, true);
    }

    private function isTokenExpired()
    {
        $decoded = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $this->token)[1]))));
        if (time() > $decoded->exp - 10) {
            // Jika waktu expired -10s telah berlalu
            return true;
        }
        return false;
    }

    /**
     * Registrasi function
     * Daftar member baru
     * @param array $form Form pendaftaran
     * @return string (JSON encoded string) hasil/error dalam format json 
     */
    public function registrasi($form)
    {
        $url = $this->baseUrl . '/profil/register';
        return $this->postRequest($url, $form);
    }

    /**
     * View function
     *
     * @param string $nomor Nomor member
     * @return string (JSON encoded string) Data profil member (satu)
     */
    public function view($nomor)
    {
        $url = $this->baseUrl . '/profil/' . $nomor;
        return $this->getRequest($url);
    }

    /**
     * Update Profil Member function
     *
     * @param string $nomor
     * @param array $data
     * @return json
     */
    public function update($nomor, $data)
    {
        $url = $this->baseUrl . '/profil/' . $nomor;
        return $this->putRequest($url, $data);
    }

    public function cari($kataKunci)
    {
        $url = $this->baseUrl . '/profil/cari';
        return $this->postRequest($url, ['kataKunci' => $kataKunci]);
    }
}
