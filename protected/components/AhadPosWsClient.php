<?php
require __DIR__ . '/../vendor/autoload.php';

class AhadPosWsClient
{
    private $client;

    function __construct()
    {
        $this->client = new WebSocket\Client("ws://localhost:48080/");
        $this->client
            ->addMiddleware(new WebSocket\Middleware\CloseHandler())
            ->addMiddleware(new WebSocket\Middleware\PingResponder());
    }

    public function sendMessage($msg)
    {
        $this->client->text($msg);
        $this->client->close();
    }
}
