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
    private string $sourceUrl;
    private string $statusFile;
    private bool $sourceConnected;

    public function __construct(string $sourceUrl, string $targetUrl, string $statusFile)
    {
        pcntl_async_signals(true);

        // Handle SIGINT (Ctrl+C) and SIGTERM (systemd stop/kill)
        pcntl_signal(SIGINT, [$this, 'handleShutdown']);
        pcntl_signal(SIGTERM, [$this, 'handleShutdown']);

        $this->statusFile = $statusFile;
        $this->sourceUrl  = $sourceUrl;

        $this->sourceClient = new Client($sourceUrl);
        $this->targetClient = new Client($targetUrl);

        // $this->setupMiddleware($this->sourceClient);
        $this->setupMiddleware($this->targetClient);
    }

    public function handleShutdown(): void
    {
        $this->sourceConnected = false;
        $this->updateStatus();
        echo "Shutting down gracefully...\n";
        exit(0);
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

        while (true) {
            $this->sourceClient = $this->ensureConnected($this->sourceUrl, 3);

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
            })->onError(function (Client $client, Connection $conn, ?\Throwable $e = null) {
                if ($e) {
                    echo "Receiver connection error: {$e->getMessage()}\n";
                } else {
                    echo "Receiver connection error: unknown reason\n";
                }
                $this->sourceConnected = false;
                $this->updateStatus();
            })->onClose(function (Client $client, Connection $conn, int $code, string $reason) {
                echo "Receiver closed (code {$code}): {$reason}\n";
                $this->sourceConnected = false;
                $this->updateStatus();
            });

            try {
                echo "Connected to {$this->sourceUrl}\n";
                $this->sourceConnected = true;
                $this->updateStatus();
                $this->sourceClient->start();
            } catch (\Throwable $e) {
                $this->sourceConnected = false;
                $this->updateStatus();
                echo "{$e->getMessage()}\n";
            }
            sleep(3);
        }
    }

    private function ensureConnected($url, $delay): Client
    {
        while (true) {
            try {
                echo "Trying to connect to {$url}.. \n";
                $client = new Client($url);
                $this->setupMiddleware($client);
                $this->sourceConnected = true;
                $this->updateStatus();
                return $client;
            } catch (\Throwable $e) {
                $this->sourceConnected = false;
                $this->updateStatus();
                echo "Connection to {$url} failed: {$e->getMessage()}\n";
                echo "Retrying in {$delay} seconds...\n";
                sleep($delay);
            }
        }
    }

    private function updateStatus(): void
    {
        $status = [
            'source'    => $this->sourceConnected,
            'timestamp' => date('c'),
        ];

        $json = json_encode($status, JSON_PRETTY_PRINT);

        $fp = fopen($this->statusFile, 'c+');
        if ($fp) {
            flock($fp, LOCK_EX);
            ftruncate($fp, 0);
            fwrite($fp, $json);
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public function isSourceConnected(): bool
    {
        return $this->sourceConnected;
    }
}
