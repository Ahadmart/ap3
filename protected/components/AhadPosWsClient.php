<?php
require __DIR__ . '/../vendor/autoload.php';

class AhadPosWsClient
{
    const TIPE_IDLE    = 10;
    const TIPE_PROCESS = 20;

    private $client;

    public function __construct()
    {
        $this->client = new WebSocket\Client('ws://localhost:48080/');
        $this->client
            ->addMiddleware(new WebSocket\Middleware\CloseHandler())
            ->addMiddleware(new WebSocket\Middleware\PingResponder());
    }

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
}
