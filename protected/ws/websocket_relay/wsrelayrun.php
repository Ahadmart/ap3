<?php

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__.'/src/WebSocketRelay.php';

use Ahadmart\WebsocketRelay\WebSocketRelay;

$config = require __DIR__ . '/../../config/db.php';

$db = new PDO($config['connectionString'], $config['username'], $config['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$q      = $db->query("SELECT nilai FROM config WHERE nama = 'toko.kode'");
$cabang = $q->fetchColumn();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // Yii::getPathOfAlias('application');
$dotenv->load();
$sentralUrl = $_ENV['WS_SERVER_CENTRAL_URL'];
$tokoUrl = $_ENV['WS_SERVER_STORE_URL'];

echo "Koneksi dari cabang: {$cabang}\n";
echo "Ke sentral: {$sentralUrl}\n";
echo "Ke toko: {$tokoUrl}\n";


$relay = new WebSocketRelay(
    "{$sentralUrl}/?cabang={$cabang}",
    $tokoUrl
);
$relay->start();
