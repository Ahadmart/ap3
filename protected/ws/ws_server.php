<?php
require __DIR__ . '/../vendor/autoload.php';

use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\MessageComponentInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

/**
 * AhadposWSServer
 * Server Web Socket untuk Customer Display
 */
class AhadposWSServer implements MessageComponentInterface
{
    protected $appName = 'Ahadpos Websocket Server';
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "{$this->appName}: New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf(
            $this->appName . ': Connection %d sending message "%s" to %d other connection%s' . "\n",
            $from->resourceId,
            $msg,
            $numRecv,
            $numRecv == 1 ? '' : 's'
        );

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "{$this->appName}: Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Error handling
        echo "{$this->appName}: An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

$port = 48080;
echo "Ahadpos Websocket Server: Started on port {$port}...\n";
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AhadposWSServer()
        )
    ),
    $port
);
$server->run();
