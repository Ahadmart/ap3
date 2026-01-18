<?php
require __DIR__ . '/../vendor/autoload.php';

class E2PayRequest
{
    private string $merchantId;
    private string $txnType;
    private string $txnChannel;
    private string $txnCurrency;
    private string $referenceNo;
    private string $txnAmount;
    private string $custName;
    private string $custEmail;
    private string $custContact;
    private string $signature;
    private string $verificationKey;
    private string $callBackUrl;
    private string $apiUrl;

    public function __construct($ref, $jumlah, $customer)
    {
        // Load from .env
        $dotenv = Dotenv\Dotenv::createImmutable(Yii::getPathOfAlias('application'));
        $dotenv->load();
        $this->apiUrl          = $_ENV['PG_APIURL'];
        $this->merchantId      = $_ENV['PG_MERCHANTID'];
        $this->txnType         = $_ENV['PG_TXNTYPE'];
        $this->txnChannel      = $_ENV['PG_TXNCHANNEL'];
        $this->txnCurrency     = $_ENV['PG_TXNCURRENCY'];
        $this->custName        = $_ENV['PG_CUSTNAME'];
        $this->custEmail       = $_ENV['PG_CUSTEMAIL'];
        $this->custContact     = $_ENV['PG_CUSTCONTACT'];
        $this->verificationKey = $_ENV['PG_VERIVICATIONKEY'];
        $this->callBackUrl     = $_ENV['PG_CALLBACKURL'];

        $this->referenceNo = $ref;
        $this->txnAmount   = $jumlah;
        $this->custName    = $customer['nama'];
        $this->custEmail   = $customer['email'] ?? $this->custEmail;
        // $this->custContact = $customer['contact'] ?: $this->custContact;
        $this->custContact = $customer['contact'] ?? $this->custContact;
        $this->signature   = md5($jumlah . $this->merchantId . $this->referenceNo . $this->verificationKey);
    }

    public function getAttribute()
    {
        return $this->txnCurrency.' '.$this->txnChannel.' '.$this->apiUrl;
    }

    /**
     * POST request function
     *
     * @param string $url
     * @param array $data
     * @return string (JSON encoded string) hasil/error dalam format json
     */
    private function postRequest($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'User-Agent: curl/7.87.0',
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r        = curl_exec($ch);
        $response = curl_getinfo($ch);
        $err      = curl_error($ch);
        curl_close($ch);
        // Yii::log('E2pay request:' . var_export($response, true));
        // Yii::log('E2pay response: ' . var_export($r, true));
        // $httpResponseCode = $response['http_code'];
        if ($err) {
            Yii::log('E2PAY request Error: ' . $err);
        } else {
            return $r;
        }
    }

    public function bayar()
    {
        $data = [
            'MerchantID'  => $this->merchantId,
            'ReferenceNo' => $this->referenceNo,
            'TxnType'     => $this->txnType,
            'TxnChannel'  => $this->txnChannel,
            'TxnCurrency' => $this->txnCurrency,
            'TxnAmount'   => $this->txnAmount,
            'CustName'    => $this->custName,
            'CustEmail'   => $this->custEmail,
            'CustContact' => $this->custContact,
            'Signature'   => $this->signature,
            'CallbackURL' => $this->callBackUrl,
        ];
        Yii::log(var_export($data, true));
        return $this->postRequest($this->apiUrl, $data);
    }
}
