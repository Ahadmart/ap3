<?php

namespace Ahadmart\WebsocketRelay;

use WebSocket\Client;
use WebSocket\Connection;
use WebSocket\Message\Message;
use WebSocket\Middleware\CloseHandler;
use WebSocket\Middleware\PingResponder;

class WebSocketRelay
{
    private Client $sourceClient;
    private Client $targetClient;

    public function __construct(string $sourceUrl, string $targetUrl)
    {
        $this->sourceClient = new Client($sourceUrl);
        $this->targetClient = new Client($targetUrl);

        $this->setupMiddleware($this->sourceClient);
        $this->setupMiddleware($this->targetClient);
    }

    private function setupMiddleware(Client $client): void
    {
        $client->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder());
    }

    public function start(): void
    {
        // defined('YII_DEBUG') or define('YII_DEBUG', true);
        // defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

        // Use correct path to yii.php
        require_once __DIR__ . '/../../../../framework/yii.php';

        // Load console config
        $config = __DIR__ . '/../../../config/console.php';

        // Init Yii console application
        \Yii::createConsoleApplication($config);

        $this->sourceClient->onText(function (Client $client, Connection $conn, Message $msg) {
            // echo var_dump($msg->getContent());
            echo "Received from source: {$msg->getContent()}\n";
            $content = json_decode($msg->getContent(), true);

            // $config         = \Config::model()->find("nama='customerdisplay.pos.enable'");
            // Tidak tergantung customer display
            $wsClientEnable = 1; // $config ? $config->nilai : null;
            if ($wsClientEnable) {
                $data = [
                    'tipe'        => \AhadPosWsClient::TIPE_QRIS_PAID,
                    'penjualanId' => $content['penjualanId'],
                    'paid'        => $content['status'] == '00' ? true : false,
                    'uId'         => $content['userId'],
                    'timestamp'   => date('Y-m-d H:i:s'),
                ];
                // $clientWS->sendJsonEncoded($data);
                // Forward to target
                $jsonData = json_encode($data);
                echo "Forward to target: {$jsonData}\n";
                $this->targetClient->text(json_encode($data));
            }
        });

        $this->sourceClient->start();
    }
}
