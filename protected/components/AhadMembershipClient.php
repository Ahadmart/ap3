<?php
class AhadMembershipClient
{
    public $url;
    public $login;
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
        $this->url   = $configArr['url'];
        $this->login = [
            'kode'     => $configArr['login.kode'],
            'nama'     => $configArr['login.nama'],
            'pwd'      => UnsafeCrypto::decrypt($configArr['login.password'], UnsafeCrypto::AHADMEMBERSHIP_KEY, true),
            'userName' => Yii::app()->user->namaLengkap
        ];
    }

    public function login()
    {
        $url = $this->url . '/toko/login';
        $ch  = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'content-type: application/json; charset=utf-8',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->login));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r        = curl_exec($ch);
        $response = curl_getinfo($ch);
        curl_close($ch);
        // $httpResponseCode = $response['http_code'];
        return $r;
    }

    private function isTokenExpired()
    {
    }
}
