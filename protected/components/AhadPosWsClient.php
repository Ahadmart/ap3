<?php

require __DIR__ . '/../vendor/autoload.php';

class AhadPosWsClient
{
    const TIPE_IDLE           = 10;
    const TIPE_PROCESS        = 20;
    const TIPE_CHECKOUT       = 30;
    const TIPE_BROSUR_UPDATE  = 40;
    const TIPE_LOGO_UPDATE    = 41;
    const TIPE_WINDOW_REFRESH = 50;

    public $global = false; // Global true jika untuk semua kasir
    private $client;
    private $coreFields;

    public function __construct()
    {
        $configCD     = Config::model()->find('nama=:nama', [':nama' => 'customerdisplay.wsport']);
        $wsPort       = $configCD->nilai;
        $this->client = new WebSocket\Client('ws://localhost:' . $wsPort);
        $this->client
            ->addMiddleware(new WebSocket\Middleware\CloseHandler())
            ->addMiddleware(new WebSocket\Middleware\PingResponder());
    }

    public function setGlobal($value)
    {
        $this->global = $value;
    }

    /**
     * Method sendMessage
     *
     * @param string $msg JSON encoded string
     *
     * @return boolean
     */
    public function sendMessage($msg)
    {
        try {
            $this->client->text($msg);
        } catch (\Throwable $e) {
            // echo "# ERROR: {$e->getMessage()} [{$e->getCode()}]\n";
            return false;
        }
        $this->client->close();
        return true;
    }

    /**
     * Method sendJsonEncoded
     *
     * @param array $data
     *
     * @return boolean
     */
    public function sendJsonEncoded($data)
    {
        $this->coreFields = [
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        if (!$this->global) {
            $this->coreFields = [
                'timestamp' => date('Y-m-d H:i:s'),
                'uId'       => Yii::app()->user->id,
            ];
        }
        $data       = array_merge($this->coreFields, $data);
        $jsonString = json_encode($data);
        try {
            $this->client->text($jsonString);
        } catch (\Throwable $e) {
            // echo "# ERROR: {$e->getMessage()} [{$e->getCode()}]\n";
            return false;
        }
        $this->client->close();
        return true;
    }
}
