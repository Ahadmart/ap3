<?php
require __DIR__ . '/../vendor/autoload.php';

class AhadPosWsClient
{
    const TIPE_IDLE     = 10;
    const TIPE_PROCESS  = 20;
    const TIPE_CHECKOUT = 30;

    private $client;
    private $coreFields;

    public function __construct()
    {
        $this->coreFields = [
            'timestamp' => date('Y-m-d H:i:s'),
            'uId'       => Yii::app()->user->id,
        ];
        $this->client = new WebSocket\Client('ws://localhost:48080/');
        $this->client
            ->addMiddleware(new WebSocket\Middleware\CloseHandler())
            ->addMiddleware(new WebSocket\Middleware\PingResponder());
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
